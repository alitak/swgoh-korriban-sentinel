<?php

namespace App\Console\Commands;

class ListCommand extends KorribanSentinelCommand
{
    protected $signature = 'ks:list {user_code} {user_name}';
    protected $description = 'Lists created snapshots';

    public function handle()
    {
        $player = $this->getPlayerByUserCode();

        $return = $player->mention . ', your saved snapshots:' . PHP_EOL;
        foreach ($player->snapshots as $snapshot) {
            $return .= $snapshot->id . ' - ' . $snapshot->created_at . ' - ' . number_format($snapshot->gp, 0, '.', ' ') . ' GP' . PHP_EOL;
        }
        echo $return;
    }
}
