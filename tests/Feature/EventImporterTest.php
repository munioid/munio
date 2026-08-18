<?php

namespace Tests\Feature;

use App\Filament\Imports\EventImporter;
use App\Models\Event\Category;
use App\Models\Event\Event;
use App\Models\Organization\Organization;
use App\Models\User;
use Filament\Actions\Imports\Models\Import;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use Throwable;

class EventImporterTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_event_and_resolves_category_by_slug(): void
    {
        $organization = Organization::query()->create([
            'name' => 'Event Org',
            'code' => 'event-org',
            'subdomain' => 'event-org',
            'domain' => null,
            'colors' => null,
        ]);

        $user = User::factory()->create();
        $user->organizations()->attach($organization->id);

        Filament::setTenant($organization, true);

        $import = Import::query()->create([
            'file_name' => 'events.csv',
            'file_path' => 'imports/events.csv',
            'importer' => EventImporter::class,
            'processed_rows' => 0,
            'total_rows' => 1,
            'successful_rows' => 0,
            'user_id' => $user->id,
        ]);

        $importer = $import->getImporter($this->columnMap(), ['organization_id' => $organization->id]);

        $importer([
            'title' => 'Summer Festival 2024',
            'slug' => 'summer-festival-2024',
            'content' => "Line one\nLine two",
            'excerpt' => 'A great summer event',
            'start_at' => '2024-07-01',
            'end_at' => '2024-07-07',
            'category_slug' => 'festival',
            'published' => 'yes',
            'published_at' => '2024-06-01 10:00:00',
            'pricing_type' => 'single',
            'price' => '50000',
            'stocks' => '100',
            'external_link' => '',
        ]);

        $event = Event::query()->where('slug', 'summer-festival-2024')->first();

        $this->assertNotNull($event);
        $this->assertSame($organization->id, $event->organization_id);
        $this->assertSame('Summer Festival 2024', $event->title);
        $this->assertSame('festival', $event->category?->slug);
        $this->assertTrue($event->published);
        $this->assertSame('2024-06-01 10:00:00', $event->published_at?->format('Y-m-d H:i:s'));
        $this->assertStringContainsString('<br />', $event->content);
        $this->assertSame('2024-07-01', $event->start_at?->format('Y-m-d'));
        $this->assertSame('2024-07-07', $event->end_at?->format('Y-m-d'));
        $this->assertEquals(50000, $event->price);
        $this->assertSame(100, $event->stocks);

        $this->assertSame(1, Category::query()->where('organization_id', $organization->id)->where('slug', 'festival')->count());
    }

    public function test_it_imports_content_as_plain_text_without_html_tags(): void
    {
        $organization = Organization::query()->create([
            'name' => 'Event Org 2',
            'code' => 'event-org-2',
            'subdomain' => 'event-org-2',
            'domain' => null,
            'colors' => null,
        ]);

        $user = User::factory()->create();
        $user->organizations()->attach($organization->id);

        Filament::setTenant($organization, true);

        $import = Import::query()->create([
            'file_name' => 'events.csv',
            'file_path' => 'imports/events.csv',
            'importer' => EventImporter::class,
            'processed_rows' => 0,
            'total_rows' => 1,
            'successful_rows' => 0,
            'user_id' => $user->id,
        ]);

        $importer = $import->getImporter($this->columnMap(), ['organization_id' => $organization->id]);

        $importer([
            'title' => 'Tech Event',
            'slug' => 'tech-event',
            'content' => 'Event content <script>alert(1)</script>',
            'start_at' => '2024-08-01',
            'end_at' => '2024-08-02',
            'category_slug' => 'tech',
            'published' => 'no',
        ]);

        $event = Event::query()->where('slug', 'tech-event')->first();

        $this->assertNotNull($event);
        $this->assertStringContainsString('&lt;script&gt;', $event->content);
        $this->assertStringNotContainsString('<script>', $event->content);
    }

    public function test_it_scopes_imports_to_the_active_organization(): void
    {
        $organizationA = Organization::query()->create([
            'name' => 'Event Org A',
            'code' => 'event-org-a',
            'subdomain' => 'event-org-a',
            'domain' => null,
            'colors' => null,
        ]);

        $organizationB = Organization::query()->create([
            'name' => 'Event Org B',
            'code' => 'event-org-b',
            'subdomain' => 'event-org-b',
            'domain' => null,
            'colors' => null,
        ]);

        $user = User::factory()->create();
        $user->organizations()->attach([$organizationA->id, $organizationB->id]);

        Filament::setTenant($organizationA, true);

        // Pre-existing event owned by organization B.
        Filament::setTenant($organizationB, true);
        Event::query()->create([
            'organization_id' => $organizationB->id,
            'title' => 'Other Org Event',
            'slug' => 'org-b-event',
            'content' => null,
            'start_at' => now(),
            'end_at' => now()->addDay(),
        ]);
        Filament::setTenant($organizationA, true);

        $import = Import::query()->create([
            'file_name' => 'events.csv',
            'file_path' => 'imports/events.csv',
            'importer' => EventImporter::class,
            'processed_rows' => 0,
            'total_rows' => 1,
            'successful_rows' => 0,
            'user_id' => $user->id,
        ]);

        $importer = $import->getImporter($this->columnMap(), ['organization_id' => $organizationA->id]);

        $importer([
            'title' => 'Imported Event',
            'slug' => 'org-a-event',
            'start_at' => '2024-08-01',
            'end_at' => '2024-08-02',
            'category_slug' => 'imported',
            'published' => 'no',
        ]);

        // Imported event belongs to organization A only.
        $orgAEvent = Event::withoutGlobalScopes()->where('organization_id', $organizationA->id)->where('slug', 'org-a-event')->first();
        $this->assertNotNull($orgAEvent);
        $this->assertSame($organizationA->id, $orgAEvent->organization_id);

        // Organization B's event is untouched.
        $orgBEvent = Event::withoutGlobalScopes()->where('organization_id', $organizationB->id)->where('slug', 'org-b-event')->first();
        $this->assertNotNull($orgBEvent);
        $this->assertSame('Other Org Event', $orgBEvent->title);

        // The tenant global scope hides organization B's event when acting as organization A.
        Filament::setTenant($organizationA, true);
        $this->assertNull(Event::query()->where('slug', 'org-b-event')->first());
        $this->assertNotNull(Event::query()->where('slug', 'org-a-event')->first());
    }

    public function test_it_updates_existing_event_by_slug(): void
    {
        $organization = Organization::query()->create([
            'name' => 'Event Org 3',
            'code' => 'event-org-3',
            'subdomain' => 'event-org-3',
            'domain' => null,
            'colors' => null,
        ]);

        $user = User::factory()->create();
        $user->organizations()->attach($organization->id);

        Filament::setTenant($organization, true);

        // Create initial event
        Event::query()->create([
            'organization_id' => $organization->id,
            'title' => 'Original Title',
            'slug' => 'event-slug',
            'content' => 'Original content',
            'start_at' => now(),
            'end_at' => now()->addDay(),
            'published' => false,
        ]);

        $import = Import::query()->create([
            'file_name' => 'events.csv',
            'file_path' => 'imports/events.csv',
            'importer' => EventImporter::class,
            'processed_rows' => 0,
            'total_rows' => 1,
            'successful_rows' => 0,
            'user_id' => $user->id,
        ]);

        $importer = $import->getImporter($this->columnMap(), ['organization_id' => $organization->id]);

        // Import same slug with updated data
        $importer([
            'title' => 'Updated Title',
            'slug' => 'event-slug',
            'start_at' => '2024-09-01',
            'end_at' => '2024-09-05',
            'category_slug' => 'updated',
            'published' => 'yes',
        ]);

        $event = Event::query()->where('slug', 'event-slug')->first();

        $this->assertSame('Updated Title', $event->title);
        $this->assertTrue($event->published);
        $this->assertSame('2024-09-01', $event->start_at?->format('Y-m-d'));
        $this->assertSame(1, Event::query()->where('organization_id', $organization->id)->count());
    }

    /**
     * @return array<string, string>
     */
    private function columnMap(): array
    {
        return [
            'title' => 'title',
            'slug' => 'slug',
            'content' => 'content',
            'excerpt' => 'excerpt',
            'start_at' => 'start_at',
            'end_at' => 'end_at',
            'category_slug' => 'category_slug',
            'published' => 'published',
            'published_at' => 'published_at',
            'pricing_type' => 'pricing_type',
            'price' => 'price',
            'stocks' => 'stocks',
            'external_link' => 'external_link',
        ];
    }
}
