<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MediaController;


Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('role:administrator');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('role:administrator');
    Route::get('profile', [AuthController::class, 'profile'])->middleware('role:administrator');
});

//------------CATEGORY ROUTE------------
Route::group([
    'middleware' =>'role:administrator',
    'prefix' => '{lang}'
], function ($router) {

    Route::post('category/{main_category?}', [CategoryController::class, 'store']);


    Route::resource('category', CategoryController::class)->except([
        'store', 'show', 'index'
    ]);
});
Route::get('category', [CategoryController::class, 'all_lang_category'])->middleware('role:administrator');
Route::get('{lang}/category/{main_category}', [CategoryController::class, 'show']);
Route::get('{lang}/category', [CategoryController::class, 'index']);

//------------MENU ROUTE------------

Route::group([
    'middleware' =>'role:administrator',
    'prefix' => '{lang}'
], function ($router) {
    Route::post('menu/{menu}', [MenuController::class, 'store_item']);
    Route::delete('menu/{menu_item}', [MenuController::class, 'destroy_item']);
    Route::put('menu/{menu_item}', [MenuController::class, 'update_item']);
});
Route::resource('menu', MenuController::class)->except([
    'show'
])->middleware('role:administrator');
Route::get('{lang}/menu/{menu}', [MenuController::class, 'show']);

//------------POSTS ROUTE------------

Route::group([
    'middleware' =>'role:administrator' ,
    'prefix' => '{lang}'
], function ($router) {

    Route::post('posts/{main_post?}', [PostController::class, 'store']);
    Route::get('posts/trash', [PostController::class, 'trash']);
    Route::post('posts/trash/{post}', [PostController::class, 'restore']);
    Route::delete('posts/trash/{post}', [PostController::class, 'delete']);

    Route::resource('posts', PostController::class)->except([
        'store', 'show', 'index'
    ]);
});
Route::get('posts', [PostController::class, 'all_lang_posts'])->middleware('role:administrator');
Route::get('{lang}/posts/{main_post}', [PostController::class, 'show']);
Route::get('{lang}/posts', [PostController::class, 'index']);

//--------PAGE ROUTE---------------

Route::group([
    'middleware' =>'role:administrator',
    'prefix' => '{lang}'
], function ($router) {

    Route::post('pages/{main_page?}', [PageController::class, 'store']);
    Route::get('pages/trash', [PageController::class, 'trash']);
    Route::post('pages/trash/{page}', [PageController::class, 'restore']);
    Route::delete('pages/trash/{page}', [PageController::class, 'delete']);

    Route::resource('pages', PageController::class)->except([
        'store', 'show', 'index'
    ]);
});
Route::get('pages', [PageController::class, 'all_lang_pages'])->middleware('role:administrator');
Route::get('{lang}/pages/{main_page}', [PageController::class, 'show']);
Route::get('{lang}/pages', [PageController::class, 'index']);

//--------MEDIA ROUTE---------------

Route::resource('media', MediaController::class)->middleware('role:administrator');


