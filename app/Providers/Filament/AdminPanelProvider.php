<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
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
                // Identité ADO PRINT — Press Cyan (voir DESIGN.md).
                // 600 = teinte de repos des actions (boutons), 500 = survol (Press Cyan Deep),
                // comme sur la boutique : #00aeef au repos, #0089c1 au survol.
                'primary' => [
                    50 => '217, 244, 255',   // Press Cyan Mist
                    100 => '165, 223, 249',
                    200 => '114, 207, 245',
                    300 => '63, 191, 242',
                    400 => '56, 189, 248',   // accent en mode sombre
                    500 => '0, 137, 193',    // Press Cyan Deep (survol)
                    600 => '0, 174, 239',    // Press Cyan (repos / marque)
                    700 => '0, 108, 152',
                    800 => '0, 80, 113',
                    900 => '0, 62, 88',
                    950 => '0, 52, 68',
                ],
            ])
            // Identité ADO PRINT : nom, favicon officiel, groupes de navigation
            // en français et barre latérale fixe (mode sombre désactivé pour
            // garantir l'identité « Fresh Ink Press », voir DESIGN.md).
            ->brandName('ADO PRINT')
            ->favicon('/favicon.ico')
            ->darkMode(false)
            ->navigationGroups([
                'Catalogue',
                'Ventes',
                'Paramètres',
            ])
            ->renderHook(
                'panels::head',
                fn (): string => implode("\n", [
                    '<link rel="icon" type="image/x-icon" href="/favicon.ico?v=2">',
                    '<link rel="icon" type="image/png" sizes="32x32" href="/icons/favicon-32.png?v=2">',
                    '<link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon.png?v=2">',
                    '<meta name="theme-color" content="#0a0a0a">',
                ]),
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
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
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
