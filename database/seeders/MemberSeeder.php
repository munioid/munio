<?php

namespace Database\Seeders;

use App\Enums\MemberAttributeTypeEnum;
use App\Enums\Membership\PackageValidityTypeEnum;
use App\Enums\MemberStatusEnum;
use App\Models\Membership\Attribute;
use App\Models\Membership\Member;
use App\Models\Membership\Package;
use App\Models\Organization\Organization;
use Filament\Facades\Filament;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organization = Organization::firstOrFail();
        Filament::setTenant($organization, true);

        $this->command->info('Proses seeding membership attribute dimulai...');
        $attributes = collect([
            ['fieldname' => 'phone', 'label' => 'Phone Number', 'type' => MemberAttributeTypeEnum::Text],
            ['fieldname' => 'address', 'label' => 'Address', 'type' => MemberAttributeTypeEnum::Text],
            ['fieldname' => 'occupation', 'label' => 'Occupation', 'type' => MemberAttributeTypeEnum::Text],
            ['fieldname' => 'city', 'label' => 'City', 'type' => MemberAttributeTypeEnum::Text],
            [
                'fieldname' => 'gender',
                'label' => 'Gender',
                'type' => MemberAttributeTypeEnum::Dropdown,
                'options' => [
                    ['code' => 'male', 'value' => 'Male'],
                    ['code' => 'female', 'value' => 'Female'],
                ],
            ],
        ])->map(fn (array $attribute) => Attribute::create([
            ...$attribute,
            'type' => $attribute['type']->value,
        ]));
        $this->command->info('Proses seeding membership attribute selesai...');

        $this->command->info('Proses seeding membership package dimulai...');
        $package = Package::create([
            'name' => 'Regular Membership',
            'code' => 'REGULAR',
            'description' => fake()->sentence(),
            'price' => 100000,
            'validity_type' => PackageValidityTypeEnum::LIFETIME->value,
            'is_active' => true,
            'is_auto_numbering' => false,
        ]);
        $this->command->info('Proses seeding membership package selesai...');

        $this->command->info('Proses seeding membership member dimulai...');
        $statuses = MemberStatusEnum::cases();

        collect(range(1, 20))->each(function () use ($package, $attributes, $statuses) {
            $member = Member::create([
                'package_id' => $package->id,
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'status' => fake()->randomElement($statuses)->value,
                'status_updated_at' => fake()->dateTimeBetween('-11 months', 'now'),
            ]);

            $attributes->each(function (Attribute $attribute) use ($member) {
                $value = $attribute->type === MemberAttributeTypeEnum::Dropdown
                    ? collect($attribute->options)->pluck('code')->random()
                    : fake()->words(2, true);

                $member->attributes()->attach($attribute->id, ['value' => $value]);
            });
        });
        $this->command->info('Proses seeding membership member selesai...');
    }
}
