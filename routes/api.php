<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/user/update', [\App\Http\Controllers\UserController::class, 'update']);
    Route::post('/user/change-password', [\App\Http\Controllers\UserController::class, 'changePassword']);

    Route::controller(\App\Http\Controllers\BaucuaController::class)->group(function () {
        Route::get('/baucua', 'getLists');
        Route::get('/baucua/start', 'startButton');
        Route::get('/baucua/stop', 'stopButton');
        Route::get('/baucua/result', 'resultGame');
        Route::get('/baucua/status', 'statusGame');
        Route::post('/baucua/addbet', 'addbet');
        Route::delete('/baucua/deletebet', 'deletebet');
        Route::get('/baucua/getbet', 'getbet');
        Route::get('/baucua/topplayer', 'topPlayer');

        Route::get('/statistics', 'statistics');
        Route::get('/filters', 'filters');
    });

});
