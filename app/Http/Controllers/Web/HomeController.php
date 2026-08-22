<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Filament\Facades\Filament;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Home');
    }
}
