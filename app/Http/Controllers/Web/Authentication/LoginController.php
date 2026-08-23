<?php

namespace App\Http\Controllers\Web\Authentication;

use App\Http\Controllers\Controller;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // If user is already authenticated, redirect to home
        if ($request->user()) {
            return redirect('/');
        }

        return Inertia::render('Authentication/Login');
    }

    public function store(Request $request)
    {
        // Validate credentials
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Attempt to authenticate
        if (Auth::guard('web')->attempt($validated)) {
            // Authentication successful
            $request->session()->regenerate();
            return response()->json(['success' => true]);
        }

        // Authentication failed
        return response()->json([
            'errors' => [
                'email' => 'Email atau password tidak sesuai',
            ],
        ], 422);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
