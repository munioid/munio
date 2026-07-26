<?php

namespace App\View\Components\Event\Events;

use App\Enums\PricingTypeEnum;
use App\Models\Event\Event;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Reservation extends Component
{

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $theme,
        public Event $event,
        public int $quantity,
        public int|float|null $price
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view($this->theme . '.components.event.events.reservation');
    }
}
