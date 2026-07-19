<?php

namespace App\Observers\Event;

use App\Enums\PricingTypeEnum;
use App\Models\Event\Reservation;

class ReservationObserver
{
    /**
     * Handle the Reservation "creating" event.
     */
    public function creating(Reservation $reservation): void
    {
        $reservation->code = $this->generateCode();

        $event = $reservation->event;
        switch ($event->pricing_type) {
            case PricingTypeEnum::SINGLE:
                $reservation->price = $event->price;
            case PricingTypeEnum::PACKAGE:
                $package = $reservation->package;
                $reservation->price = $package->price;
            default:
                $reservation->total = $reservation->quantity * $reservation->price;
        }
    }

    private function generateCode()
    {
        $date = now()->format('Ymd');

        $lastCode = Reservation::query()
            ->whereDate('created_at', today())
            ->where('code', 'like', "RSVP{$date}%")
            ->latest('code')
            ->value('code');

        $sequence = $lastCode
            ? ((int) substr($lastCode, -4)) + 1
            : 1;

        return sprintf('RSV%s%04d', $date, $sequence);
    }
}
