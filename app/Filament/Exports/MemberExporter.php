<?php

namespace App\Filament\Exports;

use App\Models\Membership\Member;
use App\Models\Organization\Organization;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Validation\ValidationException;

class MemberExporter extends Exporter
{
    protected static ?string $model = Member::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('package_code')
                ->label('Package Code')
                ->getStateUsing(fn (Member $record): string => $record->package?->code ?? ''),

            ExportColumn::make('number')
                ->label('Member Number')
                ->getStateUsing(fn (Member $record): string => $record->number ?? ''),

            ExportColumn::make('name')
                ->label('Name')
                ->getStateUsing(fn (Member $record): string => $record->name ?? ''),

            ExportColumn::make('email')
                ->label('Email')
                ->getStateUsing(fn (Member $record): string => $record->email ?? ''),

            ExportColumn::make('phone')
                ->label('Phone')
                ->getStateUsing(fn (Member $record): string => $record->attributes->firstWhere('fieldname', 'phone')?->pivot->value ?? ''),

            ExportColumn::make('at_phone')
                ->label('At Phone')
                ->getStateUsing(fn (Member $record): string => $record->attributes->firstWhere('fieldname', 'phone')?->pivot->value ?? ''),

            ExportColumn::make('at_address')
                ->label('At Address')
                ->getStateUsing(fn (Member $record): string => $record->attributes->firstWhere('fieldname', 'address')?->pivot->value ?? ''),

            ExportColumn::make('at_occupation')
                ->label('At Occupation')
                ->getStateUsing(fn (Member $record): string => $record->attributes->firstWhere('fieldname', 'occupation')?->pivot->value ?? ''),

            ExportColumn::make('at_city')
                ->label('At City')
                ->getStateUsing(fn (Member $record): string => $record->attributes->firstWhere('fieldname', 'city')?->pivot->value ?? ''),

            ExportColumn::make('at_gender')
                ->label('At Gender')
                ->getStateUsing(fn (Member $record): string => $record->attributes->firstWhere('fieldname', 'gender')?->pivot->value ?? ''),

            ExportColumn::make('status')
                ->label('Status')
                ->getStateUsing(fn (Member $record): string => $record->status ?? ''),

            ExportColumn::make('status_updated_at')
                ->label('Status Updated At')
                ->getStateUsing(fn (Member $record): ?string => $record->status_updated_at?->format('Y-m-d H:i:s')),
        ];
    }

    public static function getCompletedNotificationBody($export): string
    {
        return 'Exported '.number_format($export->successful_rows).' members.';
    }

    protected function beforeQuery(): void
    {
        $this->ensureTenant();
    }

    public function getRecords(): EloquentCollection
    {
        return Member::query()
            ->where('organization_id', $this->getOrganizationId())
            ->with(['package', 'attributes'])
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
