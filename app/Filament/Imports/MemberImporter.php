<?php

namespace App\Filament\Imports;

use App\Enums\MemberAttributeTypeEnum;
use App\Models\Membership\Attribute;
use App\Models\Membership\Member;
use App\Models\Membership\Package;
use App\Models\Organization\Organization;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MemberImporter extends Importer
{
    protected static ?string $model = Member::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('package_code')
                ->label('Package Code')
                ->requiredMapping()
                ->rules(['required', 'string'])
                ->example('REGULER'),

            ImportColumn::make('number')
                ->label('Member Number')
                ->requiredMapping()
                ->rules(['required', 'string'])
                ->example('MBR001'),

            ImportColumn::make('name')
                ->label('Name')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->example('John Doe'),

            ImportColumn::make('email')
                ->label('Email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255'])
                ->example('john@example.com'),

            ImportColumn::make('phone')
                ->label('Phone')
                ->ignoreBlankState()
                ->rules(['nullable', 'string', 'max:20'])
                ->example('08123456789'),

            ImportColumn::make('at_phone')
                ->label('At Phone')
                ->ignoreBlankState()
                ->rules(['nullable', 'string', 'max:50'])
                ->example('08123456789'),

            ImportColumn::make('at_address')
                ->label('At Address')
                ->ignoreBlankState()
                ->rules(['nullable', 'string', 'max:500'])
                ->example('Jl. Sudirman No. 1, Jakarta'),

            ImportColumn::make('at_occupation')
                ->label('At Occupation')
                ->ignoreBlankState()
                ->rules(['nullable', 'string', 'max:100'])
                ->example('Karyawan Swasta'),

            ImportColumn::make('at_city')
                ->label('At City')
                ->ignoreBlankState()
                ->rules(['nullable', 'string', 'max:100'])
                ->example('Jakarta'),

            ImportColumn::make('at_gender')
                ->label('At Gender')
                ->ignoreBlankState()
                ->rules(['nullable', 'string', 'max:20'])
                ->example('Laki-laki'),

            ImportColumn::make('status')
                ->label('Status')
                ->ignoreBlankState()
                ->rules(['nullable', 'string', 'max:50'])
                ->example('active'),

            ImportColumn::make('status_updated_at')
                ->label('Status Updated At')
                ->ignoreBlankState()
                ->rules(['nullable', 'date_format:Y-m-d H:i:s'])
                ->example('2024-01-15 10:30:00'),
        ];
    }

    public function resolveRecord(): ?Model
    {
        $number = $this->data['number'] ?? null;
        $email = $this->data['email'] ?? null;
        $organizationId = $this->getOrganizationId();

        if (blank($number) && blank($email)) {
            return new Member;
        }

        $query = Member::withoutGlobalScopes()
            ->where('organization_id', $organizationId);

        if (filled($number)) {
            $query->where('number', $number);
        } elseif (filled($email)) {
            $query->where('email', $email);
        }

        $record = $query->first();

        return $record ?: new Member;
    }

    protected function beforeValidate(): void
    {
        $this->ensureTenant();
    }

    protected function beforeSave(): void
    {
        $this->record->organization_id = $this->getOrganizationId();
        $this->ensureTenant();
    }

    protected function afterSave(): void
    {
        // Sync dynamic attributes (at_*)
        $this->syncAttributes();

        // Resolve package by code
        $this->resolvePackage();
    }

    protected function syncAttributes(): void
    {
        $organizationId = $this->getOrganizationId();

        // Get all non-private attributes for this organization
        $attributes = Attribute::withoutGlobalScopes()
            ->where('organization_id', $organizationId)
            ->where('is_private', false)
            ->get()
            ->keyBy('fieldname');

        $syncData = [];

        // Map at_* columns to attribute fieldnames
        $attributeMap = [
            'at_phone' => 'phone',
            'at_address' => 'address',
            'at_occupation' => 'occupation',
            'at_city' => 'city',
            'at_gender' => 'gender',
        ];

        foreach ($attributeMap as $column => $fieldname) {
            $value = $this->data[$column] ?? null;

            if (blank($value)) {
                continue;
            }

            $attribute = $attributes->get($fieldname);

            if (! $attribute) {
                // Log warning or skip - attribute not found for this org
                continue;
            }

            // For dropdown attributes, validate value against options
            if ($attribute->type === MemberAttributeTypeEnum::Dropdown) {
                $options = collect($attribute->options ?? [])->pluck('value', 'code')->toArray();
                if (! array_key_exists($value, $options)) {
                    // Value not in options, skip or log warning
                    continue;
                }
            }

            $syncData[$attribute->id] = ['value' => $value];
        }

        if (! empty($syncData)) {
            $this->record->attributes()->sync($syncData);
        }
    }

    protected function resolvePackage(): void
    {
        $packageCode = $this->data['package_code'] ?? null;

        if (blank($packageCode)) {
            return;
        }

        $organizationId = $this->getOrganizationId();

        $package = Package::withoutGlobalScopes()
            ->where('organization_id', $organizationId)
            ->where('code', Str::upper(trim($packageCode)))
            ->first();

        if ($package) {
            $this->record->package_id = $package->id;
            $this->record->saveQuietly();
        }
        // If package not found, leave package_id as null (or could throw error)
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $message = 'Imported '.number_format($import->successful_rows).' members.';

        if ($import->getFailedRowsCount() > 0) {
            $message .= ' '.number_format($import->getFailedRowsCount()).' rows failed validation.';
        }

        return $message;
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
                'organization_id' => 'Missing tenant context for import.',
            ]);
        }

        return (string) $organizationId;
    }
}
