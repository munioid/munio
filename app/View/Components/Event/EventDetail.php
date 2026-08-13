<?php

namespace App\View\Components\Event;

use App\Enums\PricingTypeEnum;
use App\Models\Event\Event;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EventDetail extends Component
{
    public int|float|null $price;
    public bool $pricingExternal;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $theme,
        public Event $event
    ) {
        $this->price = $event->price;
        $this->pricingExternal = $event->pricing_type === PricingTypeEnum::EXTERNAL;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view($this->theme.'.components.event.event-detail');
    }
}
