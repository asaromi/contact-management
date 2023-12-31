<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', fn() => ['message' => 'Hello World!']);
Route::post('/users', [\App\Http\Controllers\UserController::class, 'register']);
Route::post('/users/login', [\App\Http\Controllers\UserController::class, 'login']);

Route::group(['middleware' => \App\Http\Middleware\ApiAuthMiddleware::class], function () {
    Route::group(['prefix' => 'users'], function () {
        Route::get('/current', [\App\Http\Controllers\UserController::class, 'get']);
    });
});
