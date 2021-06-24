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

        $categories = Category::all();
        $posts = Post::all();

        foreach ($posts as $post) {
            DB::table('categories_posts')->insert(
                [
                    'category_id' => $categories->random()->id,
                    'post_id' => $post->id,
                ]
            );
        }

    }
}
