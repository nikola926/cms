<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator;

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
        Menu::factory()->count(3)->create();

//        for ($i=0; $i < 5; $i++) {
//            DB::table('menu_items')->insert(
//                [
//                    'menu_id' => 1 ,
//                    'parent_id' => null,
//                    'name' => $this->faker->title(10),
//                    'slug' => $this->faker->title(10),
//                    'type' => 'page' ,
//                ]
//            );
//        }

        MenuItem::factory()->count(10)->create();
    }
}
