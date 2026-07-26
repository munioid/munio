<?php

namespace App\Http\Controllers\Web\Event;

use App\Http\Controllers\Controller;
use App\Models\Event\Category;
use App\Models\Event\Event;
use Filament\Facades\Filament;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $organization = Filament::getTenant();
        $categories = Category::all();
        $theme = $request->theme;

        return view('pages.event.events', compact('theme', 'organization', 'categories'));
    }

    public function detail(string $slug)
    {
        $organization = Filament::getTenant();
        $event = Event::query()
            ->whereSlug($slug)
            ->first();

        if (!$event) {
            abort(404, 'Not found.');
        }
        
        return view('default.pages.event.detail', compact('organization', 'event'));
    }

    public function reservation(string $slug)
    {
        $organization = Filament::getTenant();
        $event = Event::query()
            ->whereSlug($slug)
            ->first();

        if (!$event) {
            abort(404, 'Not found.');
        }
        
        return view('default.pages.event.reservation', compact('organization', 'event'));
    }
}
