<?php

use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\Authentication\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
    });
Route::middleware('auth:api')
    ->group(function () {
        Route::get('profile', [ProfileController::class, 'profile']);
        Route::post('profile/update', [ProfileController::class, 'updateProfile']);
    });
