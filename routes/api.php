<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PagesController;

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

Route::get('/roles', [PermissionController::class, 'Permission']);

Route::group(['middleware' => 'role:developer'], function() {
    Route::get('/dashboard', function() {
        return 'Welcome Developer';
    });
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'pages'

], function ($router) {

    Route::get('/', [PagesController::class, 'index']);
    Route::post('/', [PagesController::class, 'store']);
    Route::get(':{id}', [PagesController::class, 'show']);
    Route::get('edit/{id}', [PagesController::class, 'edit']);
    Route::post('edit/{id}', [PagesController::class, 'update']);
    Route::delete('/:{id}', [PagesController::class, 'softDelete']);
    Route::get('/trash', [PagesController::class, 'trash']);
    Route::post('/trash/restore/{id}', [PagesController::class, 'restore']);
    Route::delete('/trash/delete/{id}', [PagesController::class, 'delete']);

});
