<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WidgetController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuItemController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::get('profile', [AuthController::class, 'profile'])->middleware('auth:api');
});

Route::group(['middleware' => 'role:developer'], function() {
    Route::get('/dashboard', function() {
        return 'Welcome Developer';
    });
});

//------------CATEGORY ROUTE------------
Route::group([
    'middleware' => 'api',
    'prefix' => '{lang}'
], function ($router) {

    Route::post('category/{main_category?}', [CategoryController::class, 'store']);
    Route::get('category/{main_category}', [CategoryController::class, 'show']);

    Route::resource('category', CategoryController::class)->except([
        'store', 'show'
    ]);
});
Route::get('category', [CategoryController::class, 'allLangCategory']);

//Route::resource('widgets', WidgetController::class);

//------------MENU ROUTE------------
Route::group([
    'middleware' => 'api',
    'prefix' => '{lang}'
], function ($router) {
    Route::get('menu{menu}', [MenuController::class, 'show']);
    Route::post('menu{menu}', [MenuItemController::class, 'store']);
});
Route::resource('menu', MenuController::class)->except([
    'show'
]);

//------------POSTS ROUTE------------
Route::group([
    'middleware' => 'api',
    'prefix' => '{lang}'
], function ($router) {

    Route::post('posts/{main_post?}', [PostController::class, 'store']);
    Route::get('posts/{main_page}', [PageController::class, 'show']);
    Route::get('posts/trash', [PostController::class, 'trash']);
    Route::post('posts/trash/{page}', [PostController::class, 'restore']);
    Route::delete('posts/trash/{page}', [PostController::class, 'delete']);

    Route::resource('posts', PostController::class)->except([
        'store', 'show'
    ]);
});
Route::get('posts', [PostController::class, 'allLangPosts']);

//--------PAGE ROUTE---------------
Route::group([
    'middleware' => 'api',
    'prefix' => '{lang}'
], function ($router) {

    Route::post('pages/{main_page?}', [PageController::class, 'store']);
    Route::get('pages/{main_page}', [PageController::class, 'show']);
    Route::get('pages/trash', [PageController::class, 'trash']);
    Route::post('pages/trash/{page}', [PageController::class, 'restore']);
    Route::delete('pages/trash/{page}', [PageController::class, 'delete']);

    Route::resource('pages', PageController::class)->except([
        'store', 'show'
    ]);
});
Route::get('pages', [PageController::class, 'allLangPages']);



