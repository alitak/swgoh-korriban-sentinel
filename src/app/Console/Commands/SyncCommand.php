<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\CategoryUnit;
use App\Models\Unit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncCommand extends Command
{
    protected $signature = 'ks:sync';
    protected $description = 'Sync characters & ships';
    private $categories = [];


    public function handle()
    {
        $this->storeUnits(json_decode(Http::get(config('swgoh.api.base') . config('swgoh.api.characters'))));
        $this->storeUnits(json_decode(Http::get(config('swgoh.api.base') . config('swgoh.api.ships'))));
        echo 'done';
    }

    private function storeUnits($unit_list)
    {
        foreach ($unit_list as $unit) {
            $local_unit = Unit::query()->updateOrCreate([
                'base_id' => $unit->base_id,
            ], [
                'combat_type' => $unit->combat_type,
                'name' => $unit->name,
                'image' => $unit->image,
                'alignment' => $unit->alignment,
                'role' => $unit->role,
            ]);
            CategoryUnit::query()->where('unit_id', $local_unit->id)->delete();

            foreach ($unit->categories as $category) {
                CategoryUnit::query()->create([
                    'unit_id' => $local_unit->id,
                    'category_id' => $this->getCategoryIdByName($category),
                ]);
            }
        }

    }

    private function getCategoryIdByName($category)
    {
        if (!isset($this->categories[$category])) {
            $cat = Category::query()->firstOrCreate(['name' => $category]);
            $this->categories[$category] = $cat->id;
        }
        return $this->categories[$category];
    }

}
