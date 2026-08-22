<?php

namespace App\Filament\Exports;

use App\Models\Event\Reservation;
use App\Models\Organization\Organization;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Validation\ValidationException;

class ReservationExporter extends Exporter
{
    protected static ?string $model = Reservation::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('code')
                ->label('Code')
                ->getStateUsing(fn (Reservation $record): string => $record->code ?? ''),

            ExportColumn::make('event_slug')
                ->label('Event Slug')
                ->getStateUsing(fn (Reservation $record): string => $record->event?->slug ?? ''),

            ExportColumn::make('package_code')
                ->label('Package Code')
                ->getStateUsing(fn (Reservation $record): string => $record->package?->code ?? ''),

            ExportColumn::make('name')
                ->label('Name')
                ->getStateUsing(fn (Reservation $record): string => $record->name ?? ''),

            ExportColumn::make('email')
                ->label('Email')
                ->getStateUsing(fn (Reservation $record): string => $record->email ?? ''),

            ExportColumn::make('price')
                ->label('Price')
                ->getStateUsing(fn (Reservation $record): ?string => $record->price !== null ? (string) $record->price : ''),

            ExportColumn::make('quantity')
                ->label('Quantity')
                ->getStateUsing(fn (Reservation $record): ?string => $record->quantity !== null ? (string) $record->quantity : ''),

            ExportColumn::make('total')
                ->label('Total')
                ->getStateUsing(fn (Reservation $record): ?string => $record->total !== null ? (string) $record->total : ''),

            ExportColumn::make('status')
                ->label('Status')
                ->getStateUsing(fn (Reservation $record): string => $record->status?->value ?? ''),

            ExportColumn::make('user_id')
                ->label('User ID')
                ->getStateUsing(fn (Reservation $record): string => $record->user_id ?? ''),
        ];
    }

    public static function getCompletedNotificationBody($export): string
    {
        return 'Exported '.number_format($export->successful_rows).' reservations.';
    }

    protected function beforeQuery(): void
    {
        $this->ensureTenant();
    }

    public function getRecords(): EloquentCollection
    {
        return Reservation::query()
            ->where('organization_id', $this->getOrganizationId())
            ->with(['event', 'package'])
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
