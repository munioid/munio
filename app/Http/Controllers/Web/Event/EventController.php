<?php

namespace App\Http\Controllers\Web\Event;

use App\Http\Controllers\Controller;
use App\Models\Event\Category;
use App\Models\Event\Event;
use App\Models\Event\Reservation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $categorySlug = $request->query('category');
        $search = $request->query('search');

        // Base query for active events
        $query = Event::query();

        // Apply category filter
        if ($categorySlug) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Apply search using Searchable trait
        $query->search($search);

        // Paginate results with relationships and cover attachment
        $events = $query
            ->with(['category', 'cover'])
            ->paginate(12)
            ->withQueryString();

        // Get all categories for filter
        $categories = Category::orderBy('name')->get();

        return Inertia::render('Event/EventsList', [
            'events' => $events,
            'categories' => $categories,
            'filters' => [
                'search' => $search,
                'category' => $categorySlug,
            ],
        ]);
    }

    public function loadMore(Request $request)
    {
        $search = $request->query('search');
        $categorySlug = $request->query('category');

        // Base query for active events
        $query = Event::query();

        // Apply category filter
        if ($categorySlug) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Apply search using Searchable trait
        $query->search($search);

        // Paginate results with relationships and cover attachment
        $events = $query
            ->with(['category', 'cover'])
            ->paginate(12)
            ->withQueryString();

        return response()->json([
            'data' => $events->items(),
            'current_page' => $events->currentPage(),
            'last_page' => $events->lastPage(),
            'total' => $events->total(),
        ]);
    }

    public function detail(string $slug, Request $request)
    {
        $event = Event::query()
            ->whereSlug($slug)
            ->with(['category', 'cover'])
            ->first();

        if (! $event) {
            abort(404, 'Not found.');
        }

        return Inertia::render('Event/EventDetail', [
            'event' => $event,
        ]);
    }

    public function reservation(string $slug, Request $request)
    {
        $event = Event::query()
            ->whereSlug($slug)
            ->with(['category', 'cover'])
            ->first();

        if (! $event) {
            abort(404, 'Not found.');
        }

        return Inertia::render('Event/Reservation', [
            'event' => $event,
        ]);
    }

    public function reservationDetail(string $code, Request $request)
    {
        $reservation = Reservation::query()
            ->where('code', $code)
            ->with(['event', 'event.category', 'event.cover'])
            ->first();

        if (! $reservation) {
            abort(404, 'Not Found.');
        }

        return Inertia::render('Event/ReservationDetail', [
            'reservation' => $reservation,
        ]);
    }
}
