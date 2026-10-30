<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Models\Setting;
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
            // Logo affiché dans la barre latérale + écran de connexion : celui
            // géré depuis Apparence du site (setting brand.logo), sinon le
            // logo par défaut de la marque. Height ajustée pour un rendu net.
            ->brandLogo(fn () => static::adminLogoUrl())
            ->brandLogoHeight('2.1rem')
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
                    // Police de la boutique pour une identité cohérente.
                    '<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">',
                    // Chartes d'interface de l'administration (voir static::adminCss()).
                    '<style>' . static::adminCss() . '</style>',
                ]),
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Accueil utiles pour un nouvel ADMIN : compte, résumé chiffré,
                // activité des commandes (graphique + dernières commandes) et
                // cartes d'accès rapide.
                Widgets\AccountWidget::class,
                \App\Filament\Widgets\StorefrontBriefing::class,
                \App\Filament\Widgets\OrdersChart::class,
                \App\Filament\Widgets\RecentOrders::class,
                \App\Filament\Widgets\StorefrontQuickLinks::class,
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

    /**
     * URL du logo d'administration : l'image téléversée depuis
     * « Apparence du site » (setting brand.logo sur le disque public),
     * sinon le logo officiel embarqué dans la charte.
     */
    public static function adminLogoUrl(): string
    {
        try {
            $logo = Setting::query()->where('key', 'brand.logo')->value('value');
        } catch (\Throwable) {
            $logo = null;
        }

        return $logo ? '/storage/' . $logo : '/images/logo.png';
    }

    /**
     * Raffinements CSS de l'administration (injectés dans <head>) : la charte
     * primaire ADO PRINT reste gérée par ->colors() ci-dessus, ce bloc met
     * en valeur les cartes, colonnes et transitions sans écraser Filament.
     */
    public static function adminCss(): string
    {
        return <<<'CSS'
        /* Typographie Headings alignée sur la boutique (Outfit). */
            .fi-logo, .fi-logo img { max-width: 11rem; object-fit: contain; }
            .fi-topbar, .fi-sidebar-header, .fi-wi-stats-overview-stat, .fi-wi-widget > .fi-ta, .fi-wi-chart {
                font-family: 'Outfit', ui-sans-serif, system-ui, sans-serif;
            }

        /* Barre latérale : dégradé très léger, séparateur cyan de la marque. */
            .fi-sidebar { background: linear-gradient(180deg, #ffffff 0%, #f4fbff 100%); }
            .fi-sidebar-header { padding-bottom: .25rem; }

        /* Éléments de navigation : transition douce et pastille au survol. */
            .fi-sidebar-nav-item a.fi-link { transition: color .2s ease, background-color .2s ease; margin-inline: .5rem; border-radius: .65rem; }
            .fi-sidebar-nav-item a.fi-link:hover { background-color: #e8f7ff; color: #0089c1; }
            .fi-sidebar-nav-item a.fi-link.fi-active { box-shadow: inset 3px 0 0 0 #00aeef; }

        /* Cartes statistiques : liseré supérieur de la marque + soulèvement au survol. */
            .fi-wi-stats-overview-stat { position: relative; overflow: hidden; border-radius: 1rem; transition: transform .2s ease, box-shadow .2s ease; }
            .fi-wi-stats-overview-stat::before { content: ""; position: absolute; inset: 0 auto auto 0; width: 100%; height: 3px; background: linear-gradient(90deg, #00aeef, #7fd8f7); }
            .fi-wi-stats-overview-stat:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(0, 174, 239, .14); }

        /* En-têtes de tableaux : plus lisibles, tirets d'accent. */
            .fi-ta-header-cell-label { font-size: .8rem; letter-spacing: .03em; }

        /* Graphique et dernières commandes : plus d'air. */
            .fi-wi-chart .fi-section-header, .fi-wi-recent-orders .fi-section-header { padding-bottom: .25rem; }

        /* Page de connexion : la carte gagne un accent de marque. */
            .fi-simple-layout-1-2-1 .fi-simple-main { border-top: 3px solid #00aeef; border-radius: 1rem; }
        CSS;
    }
}
