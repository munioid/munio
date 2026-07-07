<?php

namespace App\Providers\Filament;

use App\Http\Middleware\OnboardingMiddleware;
use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Enums\ThemeMode;
use Filament\Support\Colors\Color;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class OnboardingPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('onboarding')
            ->path('')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Onboarding/Resources'), for: 'App\\Filament\\Onboarding\\Resources')
            ->discoverPages(in: app_path('Filament/Onboarding/Pages'), for: 'App\\Filament\\Onboarding\\Pages')
            ->pages([
                \App\Filament\Onboarding\Pages\Onboarding::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Onboarding/Widgets'), for: 'App\\Filament\\Onboarding\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                OnboardingMiddleware::class
            ])
            ->defaultThemeMode(ThemeMode::Light);
    }
}