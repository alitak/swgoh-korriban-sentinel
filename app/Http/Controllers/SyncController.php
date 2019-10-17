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

    public function characters()
    {
        $characters = json_decode(url_get_contents(config('swgoh.api.base') . config('swgoh.api.characters')));
//        $characters = json_decode(\Storage::get('characters.json'));
        foreach ($characters as $character) {

            $char = \App\Models\Character::updateOrCreate(['base_id' => $character->base_id], [
                    'name' => $character->name,
                    'base_id' => $character->base_id,
                    'image' => $character->image,
                    'alignment' => $character->alignment,
                    'role' => $character->role,
            ]);
            \App\Models\CategoryCharacter::where('character_id', $char->id)->delete();

            foreach ($character->categories as $category) {
                \App\Models\CategoryCharacter::create([
                    'character_id' => $char->id,
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
