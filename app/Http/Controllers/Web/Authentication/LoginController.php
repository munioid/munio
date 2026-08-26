<?php

namespace App\Http\Controllers\Web\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\LoginRequest;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function __construct(
        private readonly NotificationService $notificationService,
    ) {}

    public function login(Request $request)
    {
        // If user is already authenticated, redirect to home
        if ($request->user()) {
            return redirect('/');
        }

        return Inertia::render('Authentication/Login');
    }

    public function store(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Flash success notification
            $this->notificationService->flashSuccess('Selamat datang kembali!', 'Login Berhasil');

            return redirect()->intended('/');
        }

        // Flash error notification
        $this->notificationService->flashError('Email atau password tidak sesuai.', 'Login Gagal');

        return back()
            ->withErrors([
                'email' => 'Email atau password tidak sesuai.',
            ])
            ->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Flash logout notification
        $this->notificationService->flashInfo('Anda telah logout.', 'Logout Berhasil');

        return redirect('/');
    }
}
