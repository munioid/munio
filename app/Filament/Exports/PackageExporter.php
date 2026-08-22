<?php

namespace App\Filament\Exports;

use App\Models\Event\Package;
use App\Models\Organization\Organization;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Validation\ValidationException;

class PackageExporter extends Exporter
{
    protected static ?string $model = Package::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('event_slug')
                ->label('Event Slug')
                ->getStateUsing(fn (Package $record): string => $record->event?->slug ?? ''),

            ExportColumn::make('name')
                ->label('Name')
                ->getStateUsing(fn (Package $record): string => $record->name ?? ''),

            ExportColumn::make('code')
                ->label('Code')
                ->getStateUsing(fn (Package $record): string => $record->code ?? ''),

            ExportColumn::make('price')
                ->label('Price')
                ->getStateUsing(fn (Package $record): ?string => $record->price !== null ? (string) $record->price : ''),

            ExportColumn::make('stocks')
                ->label('Stocks')
                ->getStateUsing(fn (Package $record): ?string => $record->stocks !== null ? (string) $record->stocks : ''),

            ExportColumn::make('booked')
                ->label('Booked')
                ->getStateUsing(fn (Package $record): ?string => $record->booked !== null ? (string) $record->booked : ''),
        ];
    }

    public static function getCompletedNotificationBody($export): string
    {
        return 'Exported '.number_format($export->successful_rows).' packages.';
    }

    protected function beforeQuery(): void
    {
        $this->ensureTenant();
    }

    public function getRecords(): EloquentCollection
    {
        return Package::query()
            ->where('organization_id', $this->getOrganizationId())
            ->with(['event'])
            ->get();
    }

    protected function ensureTenant(): void
    {
        if (Filament::getTenant() instanceof Organization) {
            return;
        }

        Filament::setTenant(Organization::query()->findOrFail($this->getOrganizationId()), true);
    }

    protected function getOrganizationId(): string
    {
        $organizationId = $this->getOptions()['organization_id'] ?? null;

        if (blank($organizationId)) {
            throw ValidationException::withMessages([
                'organization_id' => 'Missing tenant context for export.',
            ]);
        }

        return (string) $organizationId;
    }
}
