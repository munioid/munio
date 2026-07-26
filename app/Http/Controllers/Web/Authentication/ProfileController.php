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
}
