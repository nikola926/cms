<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
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
    }
}
