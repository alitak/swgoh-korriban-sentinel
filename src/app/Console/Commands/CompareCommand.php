<?php

namespace App\Console\Commands;

use App\Models\Snapshot;
use App\Models\SnapshotUnit;

class CompareCommand extends KorribanSentinelCommand
{
    protected $signature = 'ks:compare {user_code} {user_name} {snapshot_from_id} {snapshot_to_id}';
    protected $description = 'Compares two snapshots';

    public function handle()
    {
        $player = $this->getPlayerByUserCode();

        $snapshots = [
            Snapshot::query()->findOrFail($this->argument('snapshot_from_id')),
            Snapshot::query()->findOrFail($this->argument('snapshot_to_id')),
        ];

        if ($snapshots[0]->player_id != $snapshots[1]->player_id || $snapshots[0]->player_id != $player->id) {
            echo 'wrong ids, please recheck parameters!';
            exit(-1);
        }

        $return = $player->mention . ', your progress:' . PHP_EOL;
        $return .= 'Player data: ' . PHP_EOL
            . $snapshots[1]->created_at . ' -> ' . $snapshots[0]->created_at . PHP_EOL
            . number_format($snapshots[1]->gp, 0, '.', ' ') . ' GP -> ' . number_format($snapshots[0]->gp, 0, '.', ' ') . ' GP' . PHP_EOL;

        $snapshot_unit_power = SnapshotUnit::query()->where('snapshot_id', $this->argument('snapshot_from_id'))->get()->pluck('power', 'unit_id');
        $snapshot_units = SnapshotUnit::query()->where('snapshot_id', $this->argument('snapshot_to_id'))->orderBy('power', 'desc')->get();

        foreach ($snapshot_units as $snapshot_unit) {
            if (isset($snapshot_unit_power[$snapshot_unit->unit_id]) && $snapshot_unit->power == $snapshot_unit_power[$snapshot_unit->unit_id]) {
                continue;
            }

            $return .= $snapshot_unit->unit->name . PHP_EOL;
            $snapshot_unit_old = SnapshotUnit::query()->where([
                'snapshot_id' => $this->argument('snapshot_from_id'),
                'unit_id' => $snapshot_unit->unit_id,
            ])->first();

            if ($snapshot_unit_old) {
                $return .= number_format($snapshot_unit_old->power, 0, '.', ' ') . ' GP -> ' . number_format($snapshot_unit->power, 0, '.', ' ') . ' GP';

                if ($snapshot_unit_old->rarity != $snapshot_unit->rarity) {
                    $return .= ' | ' . $snapshot_unit_old->rarity . '* -> ' . $snapshot_unit->rarity . '*';
                }
                if ($snapshot_unit_old->relic_tier != $snapshot_unit->relic_tier) {
                    $return .= ' | R' . $snapshot_unit_old->relic_tier . ' -> R' . $snapshot_unit->relic_tier;
                }
                if ($snapshot_unit_old->gear_level != $snapshot_unit->gear_level) {
                    $return .= ' | G' . $snapshot_unit_old->gear_level . ' -> G' . $snapshot_unit->gear_level;
                }
                if ($snapshot_unit_old->speed != $snapshot_unit->speed) {
                    $return .= ' | speed ' . $snapshot_unit_old->speed . ' -> ' . $snapshot_unit->speed;
                }
            } else {
                $return .= 'new: ' . number_format($snapshot_unit->power, 0, '.', ' ') . ' GP | '
                    . $snapshot_unit->rarity . '* | '
                    . ($snapshot_unit->relic_tier > 1 ? 'R' . $snapshot_unit->relic_tier : 'G' . $snapshot_unit->gear_level) . ' | '
                    . 'speed ' . $snapshot_unit->speed;
            }
            $return .= PHP_EOL;
        }

        echo $return;
    }
}
