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
        return \App\Models\Snapshot::where('player_id', $player->id)->pluck('created_at', 'id')->toArray();
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
