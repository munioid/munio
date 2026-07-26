<?php

namespace App\View\Components\Event\Events;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\Component;

class ListItems extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $theme,
        public LengthAwarePaginator $events
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view($this->theme.'.components.event.events.list-items');
    }
}
