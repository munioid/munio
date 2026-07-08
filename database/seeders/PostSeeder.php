<?php

namespace Database\Seeders;

use App\Models\Blog\Category;
use App\Models\Blog\Post;
use App\Models\Blog\Tag;
use App\Models\Organization\Organization;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organization = Organization::firstOrFail();
        
        $this->command->info('Proses seeding blog kategori dimulai...');
        Category::factory(5)
            ->state([
                'organization_id' => $organization->id,
            ])
            ->create();
        $this->command->info('Proses seeding blog kategori selesai...');
        $this->command->info('Proses seeding blog tag dimulai...');
        Tag::factory(5)
            ->state([
                'organization_id' => $organization->id,
            ])
            ->create();
        $this->command->info('Proses seeding blog tag selesai...');
        $this->command->info('Proses seeding blog post dimulai...');
        Post::factory(20)
            ->state([
                'organization_id' => $organization->id,
            ])
            ->create();
        $this->command->info('Proses seeding blog post selesai...');
    }
}
