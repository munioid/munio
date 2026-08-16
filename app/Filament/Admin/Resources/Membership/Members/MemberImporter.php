<?php

namespace App\Filament\Admin\Resources\Membership\Members;

use App\Enums\MemberAttributeTypeEnum;
use App\Enums\MemberStatusEnum;
use App\Models\Membership\Attribute;
use App\Models\Membership\Member;
use App\Models\Membership\Package;
use App\Models\Organization\Organization;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MemberImporter implements ToCollection, WithBatchInserts, WithHeadingRow, WithValidation
{
    protected Organization $organization;

    public function __construct(?Organization $organization = null)
    {
        $this->organization = $organization ?? Filament::getTenant();
    }

    /**
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'package_code' => ['required', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'max:255'],
            'at_phone' => ['nullable'],
            'at_address' => ['nullable', 'string'],
            'at_occupation' => ['nullable', 'string'],
            'at_city' => ['nullable', 'string'],
            'at_gender' => ['nullable', 'string'],
            'status' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (! in_array($value, array_column(MemberStatusEnum::cases(), 'value'))) {
                    $fail("The {$attribute} is invalid.");
                }
            }],
            'status_updated_at' => ['nullable', 'date_format:Y-m-d H:i:s'],
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function collection(Collection $rows): void
    {
        if (! $this->organization) {
            throw new \RuntimeException('Tenant context is required for import.');
        }

        // Preload all attributes and packages for this organization
        $attributes = Attribute::query()
            ->where('organization_id', $this->organization->id)
            ->where('is_private', false)
            ->get()
            ->keyBy('fieldname');

        $packages = Package::query()
            ->where('organization_id', $this->organization->id)
            ->get()
            ->keyBy('code');

        // Get existing member numbers to handle duplicates
        $existingNumbers = Member::query()
            ->where('organization_id', $this->organization->id)
            ->pluck('number', 'number')
            ->toArray();

        DB::transaction(function () use ($rows, $attributes, $packages, &$existingNumbers) {
            foreach ($rows as $row) {
                $packageCode = $row['package_code'] ?? null;
                $package = $packageCode ? ($packages->get($packageCode)) : null;

                if (! $package) {
                    continue; // Skip if package not found
                }

                $number = $row['number'] ?? null;
                $isAutoNumbering = $package->is_auto_numbering;

                // If auto-numbering is enabled, generate number after creating member
                if ($isAutoNumbering) {
                    $number = null;
                }

                // Check for duplicate number
                if ($number && isset($existingNumbers[$number])) {
                    continue; // Skip duplicate
                }

                // Create member
                $member = Member::create([
                    'organization_id' => $this->organization->id,
                    'package_id' => $package->id,
                    'number' => $number,
                    'name' => $row['name'] ?? null,
                    'email' => $row['email'] ?? null,
                    'phone' => $row['phone'] ?? null,
                    'status' => $row['status'] ?? MemberStatusEnum::PENDING->value,
                    'status_updated_at' => $row['status_updated_at'] ? Carbon::parse($row['status_updated_at']) : now(),
                ]);

                if ($number) {
                    $existingNumbers[$number] = $number;
                }

                // Sync attributes
                $attributeData = [];
                foreach ($attributes as $fieldname => $attribute) {
                    $columnName = "at_{$fieldname}";
                    $value = $row[$columnName] ?? null;

                    if (filled($value)) {
                        // For dropdown attributes, validate against options
                        if ($attribute->type === MemberAttributeTypeEnum::Dropdown) {
                            $validCodes = collect($attribute->options ?? [])->pluck('code')->toArray();
                            if (! in_array($value, $validCodes)) {
                                continue; // Skip invalid dropdown value
                            }
                        }
                        $attributeData[$attribute->id] = ['value' => $value];
                    }
                }

                if (! empty($attributeData)) {
                    $member->attributes()->sync($attributeData);
                }

                // If auto-numbering, generate number now
                if ($isAutoNumbering) {
                    $member->generateNumber();
                    $existingNumbers[$member->number] = $member->number;
                }
            }
        });
    }
}
