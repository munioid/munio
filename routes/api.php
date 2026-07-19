<?php

use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\Authentication\ProfileController;
use App\Http\Controllers\Blog\CategoryController;
use App\Http\Controllers\Blog\PostController;
use App\Http\Controllers\Blog\TagController;
use App\Http\Controllers\Event\CategoryController as EventCategoryController;
use App\Http\Controllers\Event\EventController;
use App\Http\Controllers\Event\ReservationController;
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
        // Posts
        Route::prefix('posts')->group(function () {
            Route::get('/', [PostController::class, 'index']);
            Route::get('{id}', [PostController::class, 'detail']);
        });

        // Categories
        Route::get('categories', [CategoryController::class, 'index']);

        // Tags
        Route::get('tags', [TagController::class, 'index']);
    });

    // Event
    Route::prefix('events')->group(function() {
        // Categories
        Route::get('categories', [EventCategoryController::class, 'index']);

        // Reservations
        Route::prefix('reservations')->group(function() {
            Route::post('/', [ReservationController::class, 'store']);
        });
        
        // Events
        Route::get('/', [EventController::class, 'index']);
        Route::get('{id}', [EventController::class, 'detail']);
    });
});
