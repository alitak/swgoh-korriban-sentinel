<?php

namespace App\Http\Controllers;

/**
 * Description of SyncController
 *
 * @author alitak
 */
class SyncController extends Controller
{

    private $categories = [];

    public function units()
    {
        $this->storeUnits(json_decode(url_get_contents(config('swgoh.api.base') . config('swgoh.api.characters'))));
        $this->storeUnits(json_decode(url_get_contents(config('swgoh.api.base') . config('swgoh.api.ships'))));
    }

    private function storeUnits($unit_list)
    {
        foreach ($unit_list as $unit) {
            $local_unit = \App\Models\Unit::updateOrCreate([
                'base_id' => $unit->base_id,
            ], [
                'combat_type' => $unit->combat_type,
                'name' => $unit->name,
                'image' => $unit->image,
                'alignment' => $unit->alignment,
                'role' => $unit->role,
            ]);
            \App\Models\CategoryUnit::where('unit_id', $local_unit->id)->delete();

            foreach ($unit->categories as $category) {
                \App\Models\CategoryUnit::create([
                    'unit_id' => $local_unit->id,
                    'category_id' => $this->getCategoryIdByName($category),
                ]);
            }
        }

    }

    private function getCategoryIdByName($category)
    {
        if (!isset($this->categories[$category])) {
            $cat = \App\Models\Category::firstOrCreate(['name' => $category]);
            $this->categories[$category] = $cat->id;
        }
        return $this->categories[$category];
    }

}
