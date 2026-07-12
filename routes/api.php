<?php

use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\Authentication\ProfileController;
use App\Http\Controllers\Blog\PostController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
});
Route::middleware('auth:api')->group(function () {
    // Authentication
    Route::get('profile', [ProfileController::class, 'profile']);
    Route::post('profile/update', [ProfileController::class, 'updateProfile']);
});

Route::middleware('customAuth')->group(function () {
    // Blog
    Route::prefix('blog')->group(function () {
        Route::prefix('posts')->group(function () {
            Route::get('/', [PostController::class, 'index']);
            Route::get('{id}', [PostController::class, 'detail']);
        });
    });
});
