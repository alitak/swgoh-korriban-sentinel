<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $params = [
            0 => '<@528325163452858383>',
        ];

        $player = $this->getPlayerByName($params[0]);
        if (!is_object($player)) {
            return;
        }

        $snapshot = (object)[
            'id' => 2,
        ];

        /////////////////////////////
        $user_data = json_decode(url_get_contents(config('swgoh.api.base') . config('swgoh.api.player') . $player->ally_code));

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

    }

    private function getPlayerByName($name)
    {
        try {
            $player = \App\Models\Player::where('name', $name)->firstOrFail();
        } catch (Exception $e) {
            return ' is not registrated yet! Please register first with command register';
        }
        return $player;
    }

}
