<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostRelation extends Model
{
    use HasFactory;

    public function all_lang_posts(){
        return $this->hasMany(Post::class, 'main_post_id', 'id');
    }

    public function post() {
        return $this->hasMany(Post::class, 'main_post_id', 'id');
    }

    public function category_relation() {
        return $this->belongsToMany(CategoryRelation::class, 'categories_posts','post_id', 'category_id');
    }
}
