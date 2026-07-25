<?php

namespace Database\Seeders;

use App\Models\Event\Category;
use App\Models\Event\Event;
use App\Models\Organization\Organization;
use Filament\Facades\Filament;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organization = Organization::firstOrFail();
        Filament::setTenant($organization, true);

        $this->command->info('Proses seeding event kategori dimulai...');
        Category::factory(5)
            ->create();
        $this->command->info('Proses seeding event kategori selesai...');

        $this->command->info('Proses seeding event dimulai...');
        Event::factory(20)
            ->create();
        $this->command->info('Proses seeding event selesai...');
    }
}
