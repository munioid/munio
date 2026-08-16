<?php

namespace App\Services\Event;

use App\Enums\PricingTypeEnum;
use App\Models\Event\Event;
use App\Models\Event\Reservation;
use App\Models\User;
use Exception;

class ReservationService
{
    public static function createReservation(array $data)
    {
        $event = Event::find(data_get($data, 'event_id'));
        if (! $event) {
            throw new Exception('Event not found.', 404);
        }

        $reservation = new Reservation;
        $reservation->event_id = $event->id;
        $reservation->name = data_get($data, 'name');
        $reservation->email = data_get($data, 'email');
        $reservation->quantity = data_get($data, 'quantity');

        // If event has package
        if ($event->pricing_type == PricingTypeEnum::PACKAGE) {
            $package = $event->packages()
                ->find(data_get($data, 'package_id'));
            if (! $package) {
                throw new Exception('Package not found.', 404);
            }

            $reservation->package_id = $package->id;
        }

        if (data_get($data, 'user_id')) {
            $user = User::find(data_get($data, 'user_id'));
            if (! $user) {
                throw new Exception('Pengguna tidak ditemukan.', 404);
            }
            $reservation->user_id = $user->id;
        }

        $reservation->save();

        return $reservation;
    }
}
