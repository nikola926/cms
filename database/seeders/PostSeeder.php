<?php

namespace Database\Seeders;

use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use App\Models\Post;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::factory()->count(50)->create();

    }
}
