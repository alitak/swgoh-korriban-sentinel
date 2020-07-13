<?php

namespace App\Console\Commands;

use App\Models\Snapshot;
use App\Models\SnapshotUnit;
use App\Models\Unit;
use Illuminate\Support\Facades\Http;

class SnapshotCommand extends KorribanSentinelCommand
{
    protected $signature = 'ks:snapshot {user_code} {user_name}';
    protected $description = 'Create a roster snapshot';

    public function handle()
    {
        $player = $this->getPlayerByUserCode();

        $user_data = json_decode(Http::get(config('swgoh.api.base') . config('swgoh.api.player') . $player->ally_code));
        // store snapshot
        $snapshot = Snapshot::query()->firstOrCreate([
            'player_id' => $player->id,
            'gp' => $user_data->data->galactic_power,
        ]);

        // store unit statuses
        $unit_list = Unit::all()->pluck('id', 'base_id');
        foreach ($user_data->units as $unit) {
            SnapshotUnit::query()->firstOrCreate([
                'snapshot_id' => $snapshot->id,
                'unit_id' => $unit_list[$unit->data->base_id],
                'power' => $unit->data->power,
                'rarity' => $unit->data->rarity,
                'gear_level' => $unit->data->gear_level,
                'relic_tier' => $unit->data->relic_tier,
                'speed' => (int)$unit->data->stats->{5},
            ]);
        }

        echo $player->mention . ', your snapshot saved. Snapshot id: ' . $snapshot->id;

//        return $this->list($message, $params);
    }
}
