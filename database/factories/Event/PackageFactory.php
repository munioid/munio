<?php

namespace Database\Factories\Event;

use App\Models\Event\Event;
use App\Models\Event\Package;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Package>
 */
class PackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->word();
        $price = fake()->numberBetween(50000, 500000);
        $stocks = fake()->numberBetween(10, 100);

        return [
            'event_id' => Event::factory(),
            'name' => ucfirst($name).' Package',
            'code' => strtoupper(Str::random(6)),
            'price' => $price,
            'stocks' => $stocks,
            'booked' => fake()->numberBetween(0, min(10, $stocks)),
        ];
    }
}
