<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $casts = [
        'item_properties' => 'array',
    ];

    const MENU_ITEM_TYPE_POST = 'posts';
    const MENU_ITEM_TYPE_PAGE = 'pages';
    const MENU_ITEM_TYPE_CATEGORY = 'category';
    const MENU_ITEM_TYPE_EXTERNAL = 'external';

    protected $fillable = [
        'menu_id',
        'parent_id',
        'lang',
        'order',
        'name',
        'related_id',
        'item_properties',
        'type',
    ];

    public function child_items(){
        return $this->hasMany(MenuItem::class, 'parent_id', 'id');
    }
}
