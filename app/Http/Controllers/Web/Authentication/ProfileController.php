<?php

namespace App\Http\Controllers\Web\Authentication;

use App\Http\Controllers\Controller;
use Filament\Facades\Filament;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        $organization = Filament::getTenant();
        $theme = $request->theme;

        return view('pages.authentication.profile', compact('theme', 'organization'));
    }

    public function edit(Request $request) {
        $organization = Filament::getTenant();
        $theme = $request->theme;

        return view('pages.authentication.edit-profile', compact('theme', 'organization'));
    }

    public function changePassword(Request $request) {
        $organization = Filament::getTenant();
        $theme = $request->theme;

        return view('pages.authentication.change-password', compact('theme', 'organization'));
    }
}
