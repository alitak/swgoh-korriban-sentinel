<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Discord\DiscordCommandClient;
use Illuminate\Support\Facades\URL;
use SebastianBergmann\CodeCoverage\Report\PHP;

class SentinelController extends Controller
{

    public function index()
    {
        if (php_sapi_name() !== 'cli') {
            return response(view('errors.404'), 404);
        }

        $discord = new \Discord\DiscordCommandClient([
            'token' => env('DISCORD_BOT_TOKEN'),
            'description' => 'mechwart helper bot for HUNtedHUNt3rs',
            'name' => 'KS',
//            'defaultHelpCommand' => false,
            'discordOptions' => [
                'bot' => true,
//                'avatar' => env('APP_URL') . '/images/sith-inquisitor.png',
            ],
        ]);

        $discord->registerCommand('register', function ($message, $params) {
            return $this->register($message, $params);
        }, [
            'description' => 'Register the user',
            'usage' => 'ALLYCODE @MENTIONEDUSER',
        ]);

        $discord->registerCommand('ally', function ($message, $params) {
            return $this->ally($message, $params);
        }, [
            'description' => 'Get allycode of registrated user',
            'usage' => '@MENTIONEDUSER',
        ]);

        $discord->registerCommand('snapshot', function ($message, $params) {
            return $this->snapshot($message, $params);
        }, [
            'description' => 'Create a new snapshot for user',
            'usage' => '@MENTIONEDUSER',
        ]);

        $discord->registerCommand('list', function ($message, $params) {
            return $this->list($message, $params);
        }, [
            'description' => 'list snapshots for user',
            'usage' => '@MENTIONEDUSER',
        ]);

        $discord->registerCommand('compare', function ($message, $params) {
            return $this->compare($message, $params);
        }, [
            'description' => 'Compare snapshots',
            'usage' => 'snapshotid1 snapshotid2 @MENTIONEDUSER',
        ]);

        $discord->on('ready', function ($discord) {
            echo 'started' . PHP_EOL;
        });
        $discord->run();
    }

    private function register($message, $params)
    {
        if (count($params) != 2) {
            return 'wrong parameters, try help!';
        }

        $player = \App\Models\Player::firstOrNew([
            'ally_code' => $params[0],
            'name' => $params[1],
        ], []);
        $player->save();
        return ' welcome! Your ally code is ' . $player->ally_code;
    }

    private function ally($message, $params)
    {
        $player = $this->getPlayerByName($params[0]);
        if (!is_object($player)) {
            return;
        }
        return 'your ally code is ' . $player->ally_code;
    }

    private function snapshot($message, $params)
    {
        $player = $this->getPlayerByName($params[0]);
        if (!is_object($player)) {
            return;
        }

        // get user data
        $user_data = json_decode(url_get_contents(config('swgoh.api.base') . config('swgoh.api.player') . $player->ally_code));

        // store snapshot
        $snapshot = new \App\Models\Snapshot();
        $snapshot->player_id = $player->id;
        $snapshot->gp = $user_data->data->galactic_power;
        $snapshot->save();
        echo 'snapshot: ' . $snapshot->id;

        // store unit statuses
        $unit_list = \App\Models\Unit::pluck('id', 'base_id');
        foreach ($user_data->units as $unit) {
            $snapshot_unit = new \App\Models\SnapshotUnit();
            $snapshot_unit->snapshot_id = $snapshot->id;
            $snapshot_unit->unit_id = $unit_list[$unit->data->base_id];
            $snapshot_unit->power = $unit->data->power;
            $snapshot_unit->rarity = $unit->data->rarity;
            $snapshot_unit->gear_level = $unit->data->gear_level;
            $snapshot_unit->relic_tier = $unit->data->relic_tier;
            $snapshot_unit->speed = $unit->data->stats->{5};
            $snapshot_unit->save();
        }
        echo 'done' . PHP_EOL;

        return $this->list($message, $params);
    }

    private function list($message, $params)
    {
        $player = $this->getPlayerByName($params[0]);
        if (!is_object($player)) {
            return;
        }
        $return = ' the saved snapshots:' . PHP_EOL;
        foreach (\App\Models\Snapshot::where('player_id', $player->id)->orderBy('created_at', 'desc')->pluck('created_at', 'id') as $id => $snapshot) {
            $return .= $id . ' - ' . $snapshot . PHP_EOL;
        }
        return $return;
    }

    private function compare($message, $params)
    {
        $player = $this->getPlayerByName($params[2]);
        if (!is_object($player)) {
            return;
        }
        $return = 'snapshot compare:' . PHP_EOL;
        $snapshots = [
            \App\Models\Snapshot::findOrFail($params[0]),
            \App\Models\Snapshot::findOrFail($params[1]),
        ];

        $return .= 'Player data:' . PHP_EOL;
        $return .= '         ' . $snapshots[1]->created_at . ' | ' . number_format($snapshots[1]->gp, 0, '.', ' ') . ' GP' . PHP_EOL;
        $return .= '-->   ' . $snapshots[0]->created_at . ' | ' . number_format($snapshots[0]->gp, 0, '.', ' ') . ' GP' . PHP_EOL;

        $return .= PHP_EOL . 'Units:' . PHP_EOL;
        $snapshot_unit_power = \App\Models\SnapshotUnit::where('snapshot_id', $params[1])->get()->pluck('power', 'unit_id');
        $snapshot_units = \App\Models\SnapshotUnit::where('snapshot_id', $params[0])->orderBy('power', 'desc')->get();

        foreach ($snapshot_units as $snapshot_unit) {
            if (isset($snapshot_unit_power[$snapshot_unit->unit_id]) && $snapshot_unit->power == $snapshot_unit_power[$snapshot_unit->unit_id]) {
                continue;
            }

            $return .= $snapshot_unit->unit->name . ' ' . PHP_EOL;
            $snapshot_unit_old = \App\Models\SnapshotUnit::where([
                'snapshot_id' => $params[1],
                'unit_id' => $snapshot_unit->unit_id,
            ])->first();

            if ($snapshot_unit_old) {
                $return .= '         ' . number_format($snapshot_unit_old->power, 0, '.', ' ') . ' GP | '
                    . $snapshot_unit_old->rarity . '* | '
                    . ($snapshot_unit_old->relic_tier > 1 ? 'R' . $snapshot_unit_old->relic_tier : 'G' . $snapshot_unit_old->gear_level) . ' | '
                    . 'speed ' . $snapshot_unit_old->speed;
            }
            $return .= PHP_EOL;

            $return .= '-->   ' . number_format($snapshot_unit->power, 0, '.', ' ') . ' GP | '
                . $snapshot_unit->rarity . '* | '
                . ($snapshot_unit->relic_tier > 1 ? 'R' . $snapshot_unit->relic_tier : 'G' . $snapshot_unit->gear_level) . ' | '
                . 'speed ' . $snapshot_unit->speed
                . PHP_EOL;
            $return .= PHP_EOL;
        }

        return $return;

    }

    private function getPlayerByName($name)
    {
        $player = \App\Models\Player::where('name', $name)->first();
        if (!$player) {
            return ' is not registrated yet! Please register first with command register';
        }
        return $player;
    }
}
