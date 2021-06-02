<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\WidgetController;
use App\Http\Controllers\MenuController;

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


Route::group([
    'middleware' => 'api',
    'prefix' => 'pages'
], function ($router) {

    Route::get('/trash', [PagesController::class, 'trash']);
    Route::post('/trash/{page}', [PagesController::class, 'restore']);
    Route::delete('/trash/{page}', [PagesController::class, 'delete']);

});
Route::resource('pages', PagesController::class);

Route::resource('widgets', WidgetController::class);

Route::resource('menu', MenuController::class);
