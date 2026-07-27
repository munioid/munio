<?php

namespace App\View\Components\Authentication;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\Component;

class MyReservation extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $theme,
        public LengthAwarePaginator $reservations
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view($this->theme . '.components.authentication.my-reservation');
    }
}
