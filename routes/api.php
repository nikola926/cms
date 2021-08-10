<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\Client\ClientPostController;
use App\Http\Controllers\Client\ClientPageController;
use App\Http\Controllers\Client\ClientMenuController;
use App\Http\Controllers\Client\ClientCategoryController;
use App\Models\Role;


Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::get('users', [AuthController::class, 'index'])->middleware('role:administrator');
    Route::get('users/{user_id}', [AuthController::class, 'user'])->middleware('role:administrator');
    Route::post('update', [AuthController::class, 'update'])->middleware('role:administrator');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('role:administrator');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('role:administrator');
    Route::get('profile', [AuthController::class, 'profile'])->middleware('role:administrator');
    Route::delete('user/delete/{user_id}', [AuthController::class, 'delete'])->middleware('role:administrator');
});

//------------CATEGORY ROUTE------------
Route::group([
    'middleware' =>'role:administrator',
    'prefix' => '{lang}'
], function ($router) {

    Route::post('category/{main_category?}', [CategoryController::class, 'store']);
    Route::resource('category', CategoryController::class)->except([
        'store'
    ]);
});
Route::get('category', [CategoryController::class, 'all_lang_category'])->middleware('role:administrator');
Route::get('client/{lang}/category/{main_category}', [ClientCategoryController::class, 'show']);
Route::get('client/{lang}/category', [ClientCategoryController::class, 'index']);

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
Route::get('{lang}/menu/{menu}', [MenuController::class, 'show'])->middleware('role:administrator');
Route::get('client/{lang}/menu/{menu}', [ClientMenuController::class, 'show']);

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
        'store'
    ]);
});

Route::get('posts', [PostController::class, 'all_lang_posts'])->middleware('role:administrator');
Route::get('client/{lang}/posts/{main_post}', [ClientPostController::class, 'show']);
Route::get('client/{lang}/posts', [ClientPostController::class, 'index']);

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
        'store'
    ]);
});
Route::get('pages', [PageController::class, 'all_lang_pages'])->middleware('role:administrator');
Route::get('client/{lang}/pages/{main_page}', [ClientPageController::class, 'show']);
Route::get('client/{lang}/pages', [ClientPageController::class, 'index']);

//--------MEDIA ROUTE---------------

Route::get('media/images', [MediaController::class, 'images'])->middleware('role:administrator');
Route::get('media/documents', [MediaController::class, 'documents'])->middleware('role:administrator');
Route::get('media/audio', [MediaController::class, 'audio'])->middleware('role:administrator');
Route::resource('media', MediaController::class)->middleware('role:administrator');

Route::get('/all_langs', function () {
    return Config::get('languages');
});
Route::get('/all_roles', function () {
    return Role::all();
});


