<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\OnboardingPanelProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    OnboardingPanelProvider::class,
];
