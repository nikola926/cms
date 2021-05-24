<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Posts extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'featured_image_id',
        'author_id',
        'status_id',
    ];

    public function featured_image(){
        return $this->hasOne(Media::class, 'id', 'featured_image_id');
    }

    public function status(){
        return $this->hasOne(Status::class, 'id', 'status_id');
    }
    public function author(){
        return $this->hasOne(User::class, 'id', 'author_id');
    }

    public function category(){
        return $this->belongsToMany(Category::class, 'categories_posts', 'post_id', 'category_id' );
    }
}
