<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageRelation extends Model
{
    use HasFactory;

    public function all_lang_pages(){
        return $this->hasMany(Page::class, 'main_page_id', 'id');
    }
}
