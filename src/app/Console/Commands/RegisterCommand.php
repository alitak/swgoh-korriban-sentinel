<?php

namespace App\Console\Commands;

use App\Models\Player;
use Illuminate\Console\Command;

class RegisterCommand extends KorribanSentinelCommand
{
    protected $signature = 'ks:register {user_code} {user_name} {ally_code}';
    protected $description = 'Register user for Korriban Sentinel';

    public function handle()
    {
        $player = Player::query()->firstOrCreate([
            'user_code' => $this->argument('user_code'),
            'ally_code' => $this->argument('ally_code'),
        ], [
            'user_name' => $this->argument('user_name'),
        ]);
        echo 'Welcome ' . $player->mention . '! Your ally code is ' . $player->ally_code;
    }
}
