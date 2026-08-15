<?php

namespace App\Providers\Filament;

use App\Http\Middleware\AdminMiddleware;
use App\Models\Organization\Organization;
use Filament\Facades\Filament;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->brandLogo(function () {
                $tenant = Filament::getTenant();

                if ($tenant?->icon) {
                    return $tenant->icon->getPath();
                }

                return env('BRAND_ICON') ?: '/images/icon.png';
            })
            ->favicon(function () {
                $tenant = Filament::getTenant();

                if ($tenant?->favicon) {
                    return $tenant->favicon->getPath();
                }

                return env('BRAND_FAVICON') ?: '/images/favicon.png';
            })
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->discoverClusters(in: app_path('Filament/Admin/Clusters'), for: 'App\\Filament\\Admin\\Clusters')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                AdminMiddleware::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            // Tenancy
            ->tenant(Organization::class, slugAttribute: 'code', ownershipRelationship: 'organization')
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Membership')
                    ->icon('heroicon-o-user-group'),
                NavigationGroup::make()
                    ->label('Blog')
                    ->icon('heroicon-o-newspaper'),
                NavigationGroup::make()
                    ->label('Event')
                    ->icon('heroicon-o-calendar-days'),
                NavigationGroup::make()
                    ->label('Store')
                    ->icon('heroicon-o-shopping-bag'),
            ])
            ->databaseNotifications();
    }
}
