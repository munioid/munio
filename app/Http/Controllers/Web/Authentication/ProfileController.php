<?php

namespace App\Http\Controllers\Web\Authentication;

use App\Http\Controllers\Controller;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        $organization = Filament::getTenant();
        $theme = $request->theme;

        return Inertia::render('Authentication/Profile', compact('theme', 'organization'));
    }

    public function edit(Request $request)
    {
        $organization = Filament::getTenant();
        $theme = $request->theme;

        return Inertia::render('Authentication/EditProfile', compact('theme', 'organization'));
    }

    public function changePassword(Request $request)
    {
        $organization = Filament::getTenant();
        $theme = $request->theme;

        return Inertia::render('Authentication/ChangePassword', compact('theme', 'organization'));
    }

    public function myReservations(Request $request)
    {
        $organization = Filament::getTenant();
        $theme = $request->theme;

        return Inertia::render('Authentication/MyReservations', compact('theme', 'organization'));
    }
}
