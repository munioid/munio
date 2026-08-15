<?php

namespace Database\Seeders;

use App\Models\Organization\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organization = Organization::query()->updateOrCreate(
            [
                'code' => 'default',
            ],
            [
                'name' => 'Default Community',
                'subdomain' => 'default',
                'domain' => null,
            ]
        );

        $user = User::query()->updateOrCreate(
            [
                'email' => 'admin@example.com',
            ],
            [
                'name' => 'Admin',
                'password' => Hash::make('123123123'),
                'is_admin' => true,
                'is_superuser' => false,
            ]
        );

        $user->organizations()->syncWithoutDetaching([$organization->id]);
    }
}
