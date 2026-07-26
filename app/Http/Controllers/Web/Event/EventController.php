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
        $theme = $request->theme;
        $categories = Category::all();

        return view('pages.event.events', compact('theme', 'organization', 'categories'));
    }

    public function detail(string $slug, Request $request)
    {
        $organization = Filament::getTenant();
        $theme = $request->theme;
        $event = Event::query()
            ->whereSlug($slug)
            ->first();

        if (!$event) {
            abort(404, 'Not found.');
        }
        
        return view('pages.event.detail', compact('theme', 'organization', 'event'));
    }

    public function reservation(string $slug, Request $request)
    {
        $organization = Filament::getTenant();
        $theme = $request->theme;
        $event = Event::query()
            ->whereSlug($slug)
            ->first();

        if (!$event) {
            abort(404, 'Not found.');
        }
        
        return view('pages.event.reservation', compact('theme', 'organization', 'event'));
    }
}
