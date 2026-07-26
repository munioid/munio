<?php

namespace App\Http\Controllers\Web\Event;

use App\Http\Controllers\Controller;
use App\Models\Event\Category;
use Filament\Facades\Filament;

class EventController extends Controller
{
    public function index()
    {
        $organization = Filament::getTenant();
        $categories = Category::all();
        return view('default.pages.event.events', compact('organization', 'categories'));
    }
}
