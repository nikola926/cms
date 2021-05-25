<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function posts(){
        return $this->hasMany(Post::class, 'status_id', 'id');
    }
    public function pages(){
        return $this->hasMany(Page::class, 'status_id', 'id');
    }
}
