<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SebastianBergmann\CodeCoverage\Report\PHP;

class TestController extends Controller
{
    public function index()
    {
        $params = [
//            0 => 2,
//            1 => 1,
            0 => '<@528325163452858383>',
        ];
//
//        $player = $this->getPlayerByName($params[2]);
//        if (!is_object($player)) {
//            return;
//        }

        /////////////////////////////

//        return $this->getPlayerByName($params[0]);
        $player = $this->getPlayerByName($params[0]);
//        echo $player->ally_code;
//        return $player->ally_code;
        if (!is_object($player)) {
            return;
        }
        return 'your ally code is ' . $player->ally_code;
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
