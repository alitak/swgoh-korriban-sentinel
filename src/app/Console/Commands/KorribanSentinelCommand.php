<?php

namespace App\Console\Commands;

use App\Models\Player;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class KorribanSentinelCommand extends Command
{

    protected $signature = 'dummy';

    /**
     * @param int|null $user_code
     * @return Player
     */
    protected function getPlayerByUserCode(?int $user_code = null): Player
    {
        if (!$user_code) $user_code = $this->argument('user_code');

        try {
            return Player::query()->where('user_code', $user_code)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            echo ' is not registrated yet! Please register first with command register';
            exit(0);
        }
    }
}
