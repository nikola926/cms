<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use App\Models\Status;
use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $draft = new Status();
        $draft->name = 'draft';
        $draft->save();

        $publish = new Status();
        $publish->name = 'publish';
        $publish->save();

        $draft_status = Status::where('name','draft')->first();
        $publish_status = Status::where('name','publish')->first();
        $author_id = User::where('id', 1)->first();



        $pages = new Page();
        $pages->title = 'About';
        $pages->slug = 'about';
        $pages->content = 'Test content';
        $pages->featured_image_id = null;
        $pages->author_id = $author_id->id;
        $pages->status_id = $draft_status->id;
        $pages->save();

        $pages = new Page();
        $pages->title = 'Home';
        $pages->slug = 'home';
        $pages->content = 'Test content';
        $pages->featured_image_id = null;
        $pages->author_id = $author_id->id;
        $pages->status_id = $publish_status->id;
        $pages->save();


    }
}
