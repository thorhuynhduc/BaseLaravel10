<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Auth\Login\LoginController;
use App\Modules\Auth\Logout\LogoutController;
use App\Modules\Auth\Register\RegisterController;
use App\Modules\Auth\Me\MeController;

Route::post('auth/register', RegisterController::class);
Route::post('auth/login', LoginController::class);

Route::middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('auth/me', MeController::class);
        Route::delete('logout', LogoutController::class);
    });
