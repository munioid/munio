<?php

use App\Http\Controllers\Web\Blog\PostController;
use App\Http\Controllers\Web\Event\EventController;
use App\Http\Controllers\Web\HomeController;
use App\Models\Membership\Member;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'hasTenant'
])->group(function () {
    Route::get('/', [HomeController::class, 'index']);

    // Blog posts page
    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'index']);
        Route::get('{id}', [PostController::class, 'detail']);
    });

    // Events page
    Route::prefix('events')->group(function () {
        Route::get('/', [EventController::class, 'index']);
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
