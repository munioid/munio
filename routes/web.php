<?php

use App\Http\Controllers\Web\Authentication\LoginController;
use App\Http\Controllers\Web\Authentication\ProfileController;
use App\Http\Controllers\Web\Blog\PostController;
use App\Http\Controllers\Web\Event\EventController;
use App\Http\Controllers\Web\HomeController;
use App\Models\Membership\Member;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'hasTenant',
    'customAuth'
])->group(function () {
    Route::get('/', [HomeController::class, 'index']);

    // Login page
    Route::get('login', [LoginController::class, 'login'])->name('login');

    // Profile page
    Route::prefix('profile')
        ->middleware('auth:web')
        ->group(function () {
            Route::get('/', [ProfileController::class, 'profile']);
            Route::get('edit', [ProfileController::class, 'editProfile']);
        });

    // Blog posts page
    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'index']);
        Route::get('{slug}', [PostController::class, 'detail']);
    });

    // Events page
    Route::prefix('events')->group(function () {
        Route::get('/', [EventController::class, 'index']);
        Route::get('{slug}', [EventController::class, 'detail']);
        Route::get('{slug}/reservation', [EventController::class, 'reservation']);
    });
});


Route::get('/membership/vcard/{id}', function ($id) {
    $member = Member::find($id);
    $organization = $member->organization;

    return view('vcard.vcard', compact('member', 'organization'));
});

Route::get('/home', function () {
    return view('welcome');
});
