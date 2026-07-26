<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Filament\Facades\Filament;

class HomeController extends Controller
{
    public function index()
    {
        $organization = Filament::getTenant();
        $theme = 'default';
        return view('pages.home', compact('theme','organization'));
    }
}
