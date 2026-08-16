<?php

namespace App\View\Components\Event;

use App\Models\Event\Event;
use App\Models\Event\Reservation;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ReservationDetail extends Component
{
    public Event $event;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $theme,
        public Reservation $reservation
    ) {
        $this->event = $reservation->event;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view($this->theme.'.components.event.reservation-detail');
    }
}
