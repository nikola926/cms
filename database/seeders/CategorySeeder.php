<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostRelation;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use App\Models\CategoryRelation;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $langs = Config::get('languages');
        $posts = PostRelation::all();

        for ($i = 0; $i <= 10; $i++) {
            $name = 'Category ' . $i;

            $main_category_id = CategoryRelation::create();

            foreach ($langs as $lang) {
                DB::table('categories')->insert(
                    [
                        'main_category_id' => $main_category_id->id,
                        'lang' => $lang,
                        'name' => $name.' '.$lang,
                        'slug' => Str::slug($name.' '.$lang),
                    ]
                );
            }

            DB::table('categories_posts')->insert([
                'category_id' => $main_category_id->id,
                'post_id' => $posts->random()->id,
            ]);

        }
    }
}
