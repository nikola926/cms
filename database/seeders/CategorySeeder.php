<?php

namespace Database\Seeders;

use App\Models\Post;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::factory()->count(10)->create();

        for ($i=0; $i < 100; $i++) {
            DB::table('categories_posts')->insert(
                [
                    'category_id' => rand(1,10),
                    'post_id' => rand(1,50),
                ]
            );
        }

    }
}
