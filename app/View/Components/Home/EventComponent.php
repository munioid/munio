<?php

namespace App\View\Components\Home;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class EventComponent extends Component
{
    public string $theme;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public Collection $events
    ) {
        $this->theme = 'default';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view($this->theme.'.components.home.event-component');
    }
}
