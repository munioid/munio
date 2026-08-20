<?php

namespace Database\Seeders;

use App\Enums\ReservationStatusEnum;
use App\Models\Event\Event;
use App\Models\Event\Reservation;
use App\Models\Organization\Organization;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organization = Organization::firstOrFail();
        Filament::setTenant($organization, true);

        $this->command->info('Proses seeding reservation dimulai...');

        // Get events with packages
        $events = Event::with('packages')->get();

        if ($events->isEmpty()) {
            $this->command->warn('Tidak ada event ditemukan, skip seeding reservation.');
            return;
        }

        // Get or create a user for reservations
        $user = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Customer User',
                'password' => bcrypt('password'),
                'is_superuser' => false,
            ]
        );

        $statuses = [
            ReservationStatusEnum::PENDING,
            ReservationStatusEnum::PAID,
            ReservationStatusEnum::APPROVED,
        ];

        $reservations = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'quantity' => 2,
                'status' => ReservationStatusEnum::PENDING,
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'quantity' => 1,
                'status' => ReservationStatusEnum::PAID,
            ],
            [
                'name' => 'Bob Johnson',
                'email' => 'bob@example.com',
                'quantity' => 3,
                'status' => ReservationStatusEnum::APPROVED,
            ],
            [
                'name' => 'Alice Williams',
                'email' => 'alice@example.com',
                'quantity' => 1,
                'status' => ReservationStatusEnum::PENDING,
            ],
            [
                'name' => 'Charlie Brown',
                'email' => 'charlie@example.com',
                'quantity' => 2,
                'status' => ReservationStatusEnum::PAID,
            ],
        ];

        $eventIndex = 0;

        foreach ($reservations as $reservationData) {
            // Cycle through events
            $event = $events->get($eventIndex % $events->count());
            $eventIndex++;

            // Get a random package from the event, or create a default one
            $package = $event->packages()->first();
            if (! $package) {
                $this->command->warn("Event '{$event->title}' tidak memiliki package, skip reservation.");
                continue;
            }

            $price = $package->price ?? $event->price ?? 100000;
            $quantity = $reservationData['quantity'];

            Reservation::query()->firstOrCreate(
                [
                    'email' => $reservationData['email'],
                    'organization_id' => $organization->id,
                ],
                [
                    'organization_id' => $organization->id,
                    'event_id' => $event->id,
                    'package_id' => $package->id,
                    'code' => $this->generateReservationCode(),
                    'name' => $reservationData['name'],
                    'email' => $reservationData['email'],
                    'price' => $price,
                    'quantity' => $quantity,
                    'total' => $price * $quantity,
                    'status' => $reservationData['status'],
                    'user_id' => $user->id,
                ]
            );
        }

        $this->command->info('Proses seeding reservation selesai...');
    }

    /**
     * Generate a unique reservation code.
     */
    private function generateReservationCode(): string
    {
        $prefix = 'RSV';
        $timestamp = time();
        $random = random_int(1000, 9999);

        return "{$prefix}-{$timestamp}-{$random}";
    }
}
