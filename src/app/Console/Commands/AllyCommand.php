<?php

namespace App\Console\Commands;

class AllyCommand extends KorribanSentinelCommand
{
    protected $signature = 'ks:ally {user_code} {user_name}';
    protected $description = 'Get ally code for user';

    public function handle()
    {
        $player = $this->getPlayerByUserCode($this->argument('user_code'));
        echo $player->mention . ', your ally code is ' . $player->ally_code;
    }
}
