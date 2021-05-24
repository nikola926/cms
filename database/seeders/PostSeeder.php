<?php

namespace Database\Seeders;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use App\Models\Posts;

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
        PostFactory::factoryForModel('Posts')->count(50)->create();

//        $pages = new Posts();
//        $pages->title = 'About';
//        $pages->slug = 'about';
//        $pages->content = 'Test content';
//        $pages->featured_image_id = null;
//        $pages->author_id = 1;
//        $pages->status_id = 1;
//        $pages->save();
    }
}
