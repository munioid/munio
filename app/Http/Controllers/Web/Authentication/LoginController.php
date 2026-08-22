<?php

namespace App\Http\Controllers\Web\Authentication;

use App\Http\Controllers\Controller;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        return Inertia::render('Authentication/Login');
    }
}
