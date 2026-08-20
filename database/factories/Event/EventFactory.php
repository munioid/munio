<?php

namespace Database\Factories\Event;

use App\Models\Event\Category;
use App\Models\Event\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence();
        $startAt = fake()->dateTimeBetween('-1 month', '+1 month');
        $endAt = (clone $startAt)->modify('+'.fake()->numberBetween(1, 3).' days');
        $published = fake()->boolean();

        $pricingType = fake()->randomElement([
            'single',
            'external',
        ]);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $this->fakeContent(),
            'excerpt' => fake()->paragraph(),
            'start_at' => $startAt,
            'end_at' => $endAt,
            'category_id' => Category::query()->inRandomOrder()->first()?->id,
            'published' => $published,
            'published_at' => $published ? fake()->dateTimeBetween('-10 days', 'now') : null,
            'pricing_type' => $pricingType,
            'price' => $pricingType === 'single'
                ? fake()->numberBetween(50000, 500000)
                : null,
            'stocks' => $pricingType === 'single'
                ? fake()->numberBetween(10, 200)
                : null,
            'external_link' => $pricingType === 'external'
                ? fake()->url()
                : null,
        ];
    }

    /**
     * Create packages for the event (for pricing_type = package).
     */
    public function withPackages(int $count = 2): static
    {
        return $this->afterCreating(function (Event $event) use ($count) {
            if ($event->pricing_type->value === 'package') {
                $event->packages()->createMany(
                    collect(range(1, $count))->map(function ($i) {
                        return [
                            'name' => 'Package '.$i,
                            'code' => strtoupper(fake()->unique()->bothify('PKG-####')),
                            'price' => fake()->numberBetween(50000, 300000),
                            'stocks' => fake()->numberBetween(10, 100),
                            'booked' => 0,
                        ];
                    })->all()
                );
            }
        });
    }

    protected function fakeContent(): string
    {
        return sprintf(
            '<h2>%s</h2>
        <p>%s</p>

        <p>%s</p>

        <ul>
            <li>%s</li>
            <li>%s</li>
            <li>%s</li>
        </ul>

        <blockquote>%s</blockquote>

        <p>%s</p>',
            fake()->sentence(),
            fake()->paragraph(),
            fake()->paragraph(),
            fake()->sentence(),
            fake()->sentence(),
            fake()->sentence(),
            fake()->sentence(),
            fake()->paragraph()
        );
    }
}
