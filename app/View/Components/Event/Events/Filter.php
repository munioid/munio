<?php

namespace App\View\Components\Event\Events;

use App\Models\Event\Event;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Filter extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Collection $categories,
        public ?string $selectedCategory,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('default.components.event.events.filter');
    }
}
