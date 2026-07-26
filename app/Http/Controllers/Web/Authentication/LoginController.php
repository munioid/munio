<?php

namespace App\Http\Controllers\Web\Authentication;

use App\Http\Controllers\Controller;
use Filament\Facades\Filament;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $organization = Filament::getTenant();
        $theme = $request->theme;

        return view('pages.authentication.login', compact('theme', 'organization'));
    }
}
