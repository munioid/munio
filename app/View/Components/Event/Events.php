<?php

namespace App\View\Components\Event;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\Component;

class Events extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $theme,
        public Collection $categories,
        public LengthAwarePaginator $events,
        public ?string $selectedCategory = null
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view($this->theme . '.components.event.events');
    }
}
