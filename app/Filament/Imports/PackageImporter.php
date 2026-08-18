<?php

namespace App\Filament\Imports;

use App\Models\Event\Package;
use App\Models\Event\Event;
use App\Models\Organization\Organization;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PackageImporter extends Importer
{
    protected static ?string $model = Package::class;

    protected array $eventSlugMap = [];

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('event_slug')
                ->label('Event Slug')
                ->requiredMapping()
                ->rules(['required', 'string'])
                ->guess(['event', 'event_slug'])
                ->example('winter-expo'),

            ImportColumn::make('name')
                ->label('Package Name')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->example('VIP Pass | Regular | Standard'),

            ImportColumn::make('code')
                ->label('Package Code')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->example('VIP-001 | REG-001'),

            ImportColumn::make('price')
                ->label('Price')
                ->ignoreBlankState()
                ->castStateUsing(fn (mixed $state): mixed => filled($state) ? (float) $state : null)
                ->rules(['nullable', 'numeric', 'min:0'])
                ->example('100000 (VIP) | 50000 (Regular)'),

            ImportColumn::make('stocks')
                ->label('Stocks')
                ->ignoreBlankState()
                ->castStateUsing(fn (mixed $state): mixed => filled($state) ? (int) $state : null)
                ->rules(['nullable', 'integer', 'min:0'])
                ->example('50 (VIP) | 200 (Regular)'),

            ImportColumn::make('booked')
                ->label('Booked')
                ->ignoreBlankState()
                ->castStateUsing(fn (mixed $state): mixed => filled($state) ? (int) $state : null)
                ->rules(['nullable', 'integer', 'min:0'])
                ->example('10 (current bookings)'),
        ];
    }

    public function resolveRecord(): ?Model
    {
        $eventSlug = $this->normalizeSlug($this->data['event_slug'] ?? null);
        $code = $this->data['code'] ?? null;

        if (blank($eventSlug) || blank($code)) {
            return new Package;
        }

        $event = Event::query()
            ->where('organization_id', $this->getOrganizationId())
            ->where('slug', $eventSlug)
            ->first();

        if (! $event) {
            return new Package;
        }

        $record = Package::query()
            ->where('event_id', $event->id)
            ->where('code', $code)
            ->first();

        return $record ?: new Package;
    }

    protected function beforeValidate(): void
    {
        $this->ensureTenant();
    }

    protected function beforeSave(): void
    {
        $this->record->organization_id = $this->getOrganizationId();
        $this->ensureTenant();

        // Resolve event by slug before saving
        $eventSlug = $this->normalizeSlug($this->data['event_slug'] ?? null);
        if (filled($eventSlug)) {
            $event = $this->findEventBySlug($eventSlug);
            if ($event) {
                $this->record->event_id = $event->id;
            }
        }

        // Remove event_slug from the data to prevent mass assignment error
        unset($this->data['event_slug']);
    }

    public function findEventBySlug(mixed $state): ?Event
    {
        $slug = $this->normalizeSlug($state);

        if (blank($slug)) {
            return null;
        }

        $organizationId = $this->getOrganizationId();
        $this->ensureTenant();

        $event = Event::withoutGlobalScopes()
            ->where('organization_id', $organizationId)
            ->where('slug', $slug)
            ->first();

        return $event;
    }

    protected function ensureTenant(): void
    {
        if (Filament::getTenant() instanceof Organization) {
            return;
        }

        Filament::setTenant(Organization::query()->findOrFail($this->getOrganizationId()), true);
    }

    protected function normalizeSlug(mixed $state): ?string
    {
        $slug = Str::slug(trim((string) $state));

        return filled($slug) ? $slug : null;
    }

    protected function getOrganizationId(): string
    {
        $organizationId = $this->getOptions()['organization_id'] ?? null;

        if (blank($organizationId)) {
            throw ValidationException::withMessages([
                'organization_id' => 'Missing tenant context for import.',
            ]);
        }

        return (string) $organizationId;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $message = 'Imported '.number_format($import->successful_rows).' packages.';

        if ($import->getFailedRowsCount() > 0) {
            $message .= ' '.number_format($import->getFailedRowsCount()).' rows failed validation.';
        }

        return $message;
    }
}
