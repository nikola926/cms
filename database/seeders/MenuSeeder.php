<?php

namespace Database\Seeders;

use App\Models\CategoryRelation;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $faker;

    public function run()
    {
        $langs = Config::get('languages');
        $categories = CategoryRelation::all();

        $main_menu = Menu::create([
            'name' => 'Main menu',
        ]);

        foreach ($langs as $lang){
            for ($i=1; $i <= 5; $i++){
                DB::table('menu_items')->insert(
                    [
                        'menu_id' => $main_menu->id,
                        'lang' => $lang,
                        'order' => $i,
                        'name' => 'Menu item '.$i,
                        'related_id' => $categories->random()->id,
                        'type' => MenuItem::MENU_ITEM_TYPE_CATEGORY,
                    ]
                );
            }
        }


    }
}
