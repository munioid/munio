<?php

namespace Database\Factories\Blog;

use App\Models\Blog\Category;
use App\Models\Blog\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence();
        $published = fake()->boolean();

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $this->fakeContent(),
            'excerpt' => fake()->paragraph(),
            'source' => fake()->url(),
            'category_id' => Category::query()->inRandomOrder()->first()?->id,
            'published' => $published,
            'published_at' => $published ? fake()->dateTimeBetween('-10 months', 'now') : null,
        ];
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
