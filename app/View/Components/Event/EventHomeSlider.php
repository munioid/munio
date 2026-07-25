<?php

namespace App\View\Components\Event;

use App\Models\Event\Event;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class EventHomeSlider extends Component
{
    public Collection $events;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->events = Event::query()
            ->published()
            ->orderBy('published_at', 'desc')
            ->take(6)
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.event.event-home-slider', [$this->events]);
    }
}
