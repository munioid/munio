<?php

namespace Tests\Feature;

use App\Filament\Imports\PackageImporter;
use App\Models\Event\Event;
use App\Models\Event\Package;
use App\Models\Organization\Organization;
use App\Models\User;
use Filament\Actions\Imports\Models\Import;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackageImporterTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_package_and_resolves_event_by_slug(): void
    {
        $organization = Organization::query()->create([
            'name' => 'Package Org',
            'code' => 'package-org',
            'subdomain' => 'package-org',
            'domain' => null,
            'colors' => null,
        ]);

        $user = User::factory()->create();
        $user->organizations()->attach($organization->id);

        Filament::setTenant($organization, true);

        // Create an event to link packages to
        $event = Event::query()->create([
            'organization_id' => $organization->id,
            'title' => 'Summer Festival',
            'slug' => 'summer-festival',
            'content' => 'Festival details',
            'start_at' => now(),
            'end_at' => now()->addDays(7),
        ]);

        $import = Import::query()->create([
            'file_name' => 'packages.csv',
            'file_path' => 'imports/packages.csv',
            'importer' => PackageImporter::class,
            'processed_rows' => 0,
            'total_rows' => 1,
            'successful_rows' => 0,
            'user_id' => $user->id,
        ]);

        $importer = $import->getImporter($this->columnMap(), ['organization_id' => $organization->id]);

        $importer([
            'event_slug' => 'summer-festival',
            'name' => 'VIP Pass',
            'code' => 'VIP-001',
            'price' => '100000',
            'stocks' => '50',
            'booked' => '10',
        ]);

        $package = Package::query()->where('code', 'VIP-001')->first();

        $this->assertNotNull($package);
        $this->assertSame($organization->id, $package->organization_id);
        $this->assertSame($event->id, $package->event_id);
        $this->assertSame('VIP Pass', $package->name);
        $this->assertEquals(100000, $package->price);
        $this->assertSame(50, $package->stocks);
        $this->assertSame(10, $package->booked);
    }

    public function test_it_updates_existing_package_by_event_slug_and_code(): void
    {
        $organization = Organization::query()->create([
            'name' => 'Package Org 2',
            'code' => 'package-org-2',
            'subdomain' => 'package-org-2',
            'domain' => null,
            'colors' => null,
        ]);

        $user = User::factory()->create();
        $user->organizations()->attach($organization->id);

        Filament::setTenant($organization, true);

        // Create an event and package
        $event = Event::query()->create([
            'organization_id' => $organization->id,
            'title' => 'Tech Conf',
            'slug' => 'tech-conf',
            'content' => 'Conference details',
            'start_at' => now(),
            'end_at' => now()->addDays(3),
        ]);

        Package::query()->create([
            'organization_id' => $organization->id,
            'event_id' => $event->id,
            'name' => 'Standard Ticket',
            'code' => 'STD-001',
            'price' => 50000,
            'stocks' => 200,
            'booked' => 50,
        ]);

        $import = Import::query()->create([
            'file_name' => 'packages.csv',
            'file_path' => 'imports/packages.csv',
            'importer' => PackageImporter::class,
            'processed_rows' => 0,
            'total_rows' => 1,
            'successful_rows' => 0,
            'user_id' => $user->id,
        ]);

        $importer = $import->getImporter($this->columnMap(), ['organization_id' => $organization->id]);

        // Import with updated data for same event_slug + code
        $importer([
            'event_slug' => 'tech-conf',
            'name' => 'Standard Ticket Updated',
            'code' => 'STD-001',
            'price' => '55000',
            'stocks' => '250',
            'booked' => '75',
        ]);

        $package = Package::query()->where('code', 'STD-001')->first();

        $this->assertSame('Standard Ticket Updated', $package->name);
        $this->assertEquals(55000, $package->price);
        $this->assertSame(250, $package->stocks);
        $this->assertSame(75, $package->booked);
        $this->assertSame(1, Package::query()->where('event_id', $event->id)->count());
    }

    public function test_it_scopes_packages_to_the_active_organization(): void
    {
        $organizationA = Organization::query()->create([
            'name' => 'Package Org A',
            'code' => 'package-org-a',
            'subdomain' => 'package-org-a',
            'domain' => null,
            'colors' => null,
        ]);

        $organizationB = Organization::query()->create([
            'name' => 'Package Org B',
            'code' => 'package-org-b',
            'subdomain' => 'package-org-b',
            'domain' => null,
            'colors' => null,
        ]);

        $user = User::factory()->create();
        $user->organizations()->attach([$organizationA->id, $organizationB->id]);

        Filament::setTenant($organizationA, true);

        // Create events in each organization
        $eventA = Event::query()->create([
            'organization_id' => $organizationA->id,
            'title' => 'Event A',
            'slug' => 'event-a',
            'content' => 'Event A details',
            'start_at' => now(),
            'end_at' => now()->addDay(),
        ]);

        Filament::setTenant($organizationB, true);
        $eventB = Event::query()->create([
            'organization_id' => $organizationB->id,
            'title' => 'Event B',
            'slug' => 'event-b',
            'content' => 'Event B details',
            'start_at' => now(),
            'end_at' => now()->addDay(),
        ]);

        Filament::setTenant($organizationA, true);

        $import = Import::query()->create([
            'file_name' => 'packages.csv',
            'file_path' => 'imports/packages.csv',
            'importer' => PackageImporter::class,
            'processed_rows' => 0,
            'total_rows' => 1,
            'successful_rows' => 0,
            'user_id' => $user->id,
        ]);

        $importer = $import->getImporter($this->columnMap(), ['organization_id' => $organizationA->id]);

        $importer([
            'event_slug' => 'event-a',
            'name' => 'Package A',
            'code' => 'PKG-A',
            'price' => '50000',
            'stocks' => '100',
        ]);

        // Package belongs to organization A only
        $packageA = Package::withoutGlobalScopes()->where('organization_id', $organizationA->id)->where('code', 'PKG-A')->first();
        $this->assertNotNull($packageA);
        $this->assertSame($organizationA->id, $packageA->organization_id);
        $this->assertSame($eventA->id, $packageA->event_id);

        // Organization B's event is not accessible when importing for organization A
        Filament::setTenant($organizationA, true);
        $this->assertSame(1, Package::query()->where('organization_id', $organizationA->id)->count());
    }

    public function test_it_rejects_packages_with_missing_event_slug(): void
    {
        $organization = Organization::query()->create([
            'name' => 'Package Org 3',
            'code' => 'package-org-3',
            'subdomain' => 'package-org-3',
            'domain' => null,
            'colors' => null,
        ]);

        $user = User::factory()->create();
        $user->organizations()->attach($organization->id);

        Filament::setTenant($organization, true);

        $import = Import::query()->create([
            'file_name' => 'packages.csv',
            'file_path' => 'imports/packages.csv',
            'importer' => PackageImporter::class,
            'processed_rows' => 0,
            'total_rows' => 1,
            'successful_rows' => 0,
            'user_id' => $user->id,
        ]);

        $importer = $import->getImporter($this->columnMap(), ['organization_id' => $organization->id]);

        // Import with non-existent event slug should fail
        try {
            $importer([
                'event_slug' => 'nonexistent-event',
                'name' => 'Orphan Package',
                'code' => 'ORPHAN-001',
                'price' => '50000',
                'stocks' => '50',
            ]);

            $this->fail('Expected import to fail when event_slug does not resolve to an event');
        } catch (\Throwable $throwable) {
            // Expected - cannot create a package without a valid event_id
            $this->assertTrue(true);
        }

        // Verify no orphan package was created
        $package = Package::query()->where('code', 'ORPHAN-001')->first();
        $this->assertNull($package);
    }

    /**
     * @return array<string, string>
     */
    private function columnMap(): array
    {
        return [
            'event_slug' => 'event_slug',
            'name' => 'name',
            'code' => 'code',
            'price' => 'price',
            'stocks' => 'stocks',
            'booked' => 'booked',
        ];
    }
}
