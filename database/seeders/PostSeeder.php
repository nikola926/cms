<?php

namespace Database\Seeders;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use App\Models\Post;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Generator;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        Post::factory()->count(50)->create();

        for ($i=0; $i < 100; $i++) {
            $title = 'Title '.$i;
            DB::table('post_translations')->insert(
                [
                    'main_post_id' => rand(1, 5),
                    'lang' => Arr::random(['rs','en']),
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'content' => 'lorem ipsum',
                    'author_id' => rand(1, 2),
                    'status_id' => rand(1, 2),
                ]
            );
        }




    }
}
