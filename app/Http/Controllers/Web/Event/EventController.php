<?php

namespace App\Http\Controllers\Web\Event;

use App\Http\Controllers\Controller;
use App\Models\Event\Category;
use App\Models\Event\Event;
use Filament\Facades\Filament;

class EventController extends Controller
{
    public function index()
    {
        $organization = Filament::getTenant();
        $categories = Category::all();
        return view('default.pages.event.events', compact('organization', 'categories'));
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
