<?php

namespace Database\Seeders;

/**
 * Taxonomie officielle du catalogue ADO PRINT : 9 familles de produits,
 * chacune déclinée en sous-catégories. Les slugs sont dérivés des noms
 * (Str::slug) — le seeder vérifie qu'aucun doublon n'apparaît.
 */
class AdoPrintTaxonomy
{
    /**
     * Familles (niveau 1) et leurs sous-catégories (niveau 2).
     */
    public static function families(): array
    {
        return [
            [
                'name'        => 'Impression Papier & Bureautique',
                'description' => "Tout l'imprimé de bureau et de communication : cartes de visite, flyers, brochures, papeterie d'entreprise, calendriers et documents professionnels, imprimés à Casablanca avec un prix dégressif selon la quantité.",
                'children'    => [
                    'Cartes de visite',
                    'Flyers & Prospectus',
                    'Dépliants',
                    'Brochures & Catalogues',
                    'Affiches & Posters',
                    'Papier à en-tête',
                    'Enveloppes personnalisées',
                    'Chemises à rabat',
                    'Blocs-notes & Carnets',
                    'Calendriers & Agendas',
                    'Livres & Magazines',
                    'Menus de restaurant',
                    'Invitations & Faire-part',
                    'Impression de documents',
                    'Reliure & Plastification',
                ],
            ],
            [
                'name'        => 'Impression Grand Format',
                'description' => "Bâches, vinyles, panneaux rigides et supports grand format : une impression haute définition et résistante aux intempéries pour vos façades, véhicules, stands et chantiers.",
                'children'    => [
                    'Bâches publicitaires',
                    'Bâches microperforées',
                    'Roll-ups & Kakémonos',
                    'Posters grand format',
                    'Vinyles adhésifs',
                    'Vinyles microperforés',
                    'Stickers grand format',
                    'Impression sur toile (Canvas)',
                    'Papier peint personnalisé',
                    'Habillage de vitrines',
                    'Habillage de véhicules',
                    'Impression sur PVC Forex',
                    'Impression sur Dibond',
                    'Impression sur Akilux',
                    'Impression UV sur supports rigides',
                ],
            ],
            [
                'name'        => 'Impression Textile & Personnalisation',
                'description' => "Textiles personnalisés pour vos équipes, vos événements et votre boutique : sérigraphie, DTF, sublimation, flocage et broderie sur tous les supports vestimentaires.",
                'children'    => [
                    'T-shirts personnalisés',
                    'Polos personnalisés',
                    'Sweats & Hoodies',
                    'Casquettes personnalisées',
                    'Tote bags & Sacs textiles',
                    'Tenues professionnelles',
                    'Uniformes scolaires',
                    'Vêtements de sport',
                    'Impression DTF',
                    'Sublimation textile',
                    'Flocage textile',
                    'Sérigraphie textile',
                    'Broderie personnalisée',
                    'Impression textile grand format',
                ],
            ],
            [
                'name'        => 'Packaging & Emballage',
                'description' => "Emballages qui valorisent vos produits : boîtes sur mesure, sacs, étiquettes, coffrets et packaging e-commerce, conçus et fabriqués selon vos dimensions.",
                'children'    => [
                    'Boîtes personnalisées',
                    'Boîtes cadeaux',
                    'Boîtes alimentaires',
                    'Boîtes en carton ondulé',
                    'Sacs en papier',
                    'Sacs kraft personnalisés',
                    'Étiquettes produits',
                    'Étiquettes adhésives en rouleaux',
                    'Stickers packaging',
                    'Sleeves & Fourreaux',
                    'Coffrets personnalisés',
                    'Emballages cosmétiques',
                    'Packaging e-commerce',
                    "Papier d'emballage personnalisé",
                ],
            ],
            [
                'name'        => 'Signalétique & Enseignes',
                'description' => "Enseignes lumineuses ou non, lettres découpées, caissons et signalétique intérieure et extérieure : nous fabriquons et posons l'identité visuelle de votre point de vente.",
                'children'    => [
                    'Enseignes lumineuses',
                    'Enseignes non lumineuses',
                    'Lettres 3D',
                    'Lettres boîtiers',
                    'Enseignes en Alucobond',
                    'Caissons lumineux',
                    'Panneaux publicitaires',
                    'Plaques professionnelles',
                    'Plaques de porte',
                    'Signalétique intérieure',
                    'Signalétique extérieure',
                    'Panneaux directionnels',
                    'Totems publicitaires',
                    'Signalétique de sécurité',
                    'Habillage de façade',
                ],
            ],
            [
                'name'        => 'Objets Publicitaires & Cadeaux',
                'description' => "Goodies et cadeaux d'entreprise marqués à votre logo : mugs, stylos, textiles, clés USB, trophées et coffrets, du petit tirage à la grande série.",
                'children'    => [
                    'Mugs personnalisés',
                    'Stylos publicitaires',
                    'Porte-clés personnalisés',
                    'Badges personnalisés',
                    'Tours de cou & Lanyards',
                    'Cartes PVC personnalisées',
                    'Gourdes & Bouteilles',
                    'Tapis de souris',
                    'Coques de téléphone',
                    'Magnets personnalisés',
                    'Trophées & Médailles',
                    'Carnets publicitaires',
                    'Clés USB personnalisées',
                    "Cadeaux d'entreprise",
                    'Coffrets cadeaux personnalisés',
                ],
            ],
            [
                'name'        => 'Impression Événementielle',
                'description' => "Tout l'habillage d'un événement : stands, photocalls, comptoirs, drapeaux, banderoles et supports promotionnels, livrés prêts à installer.",
                'children'    => [
                    "Stands d'exposition",
                    'Photocalls & Backdrops',
                    'Comptoirs promotionnels',
                    'Roll-ups événementiels',
                    'X-Banners & L-Banners',
                    'Drapeaux publicitaires',
                    'Oriflammes',
                    'Banderoles événementielles',
                    'Badges événementiels',
                    "Bracelets d'identification",
                    'Billets & Tickets',
                    'Invitations événementielles',
                    'Décoration événementielle',
                    'Supports PLV',
                    'Présentoirs publicitaires',
                ],
            ],
            [
                'name'        => 'Conception Graphique & Design',
                'description' => "Notre studio crée et prépare vos fichiers : logo, identité visuelle, charte graphique, packaging, visuels réseaux sociaux et fichiers prêts pour l'impression.",
                'children'    => [
                    'Création de logo',
                    'Identité visuelle',
                    'Charte graphique',
                    'Design de cartes de visite',
                    'Conception de flyers',
                    'Conception de catalogues',
                    'Design packaging',
                    'Design textile',
                    "Design d'enseignes",
                    'Conception de stands',
                    'Visuels pour réseaux sociaux',
                    'Retouche photo',
                    'Vectorisation de logo',
                    "Préparation de fichiers d'impression",
                    'Modélisation 2D & 3D',
                ],
            ],
            [
                'name'        => 'Gravure & Découpe CNC / Laser',
                'description' => "Atelier de découpe et gravure CNC et laser : bois, MDF, PVC, Forex, Plexiglas et métal, pour des lettres, trophées, plaques et objets décoratifs sur mesure.",
                'children'    => [
                    'Découpe CNC bois',
                    'Découpe CNC MDF',
                    'Découpe CNC PVC & Forex',
                    'Fraisage CNC',
                    'Gravure CNC 2D',
                    'Gravure CNC 3D',
                    'Découpe Laser CO2',
                    'Gravure Laser CO2',
                    'Gravure Laser Fibre',
                    'Marquage Laser Métal',
                    'Découpe Plexiglas',
                    'Découpe de lettres personnalisées',
                    'Fabrication de lettres 3D',
                    'Gravure de plaques professionnelles',
                    'Fabrication de trophées',
                    'Objets décoratifs découpés',
                    "Gravure d'objets personnalisés",
                ],
            ],
        ];
    }

    /**
     * Noms affichés avec leurs caractères d'origine (CO₂) — les slugs restent
     * simples à saisir pour les urls.
     */
    public static function displayNames(): array
    {
        return [
            'decoupe-laser-co2' => 'Découpe Laser CO₂',
            'gravure-laser-co2' => 'Gravure Laser CO₂',
        ];
    }

    /**
     * Rattachement des produits existants aux sous-catégories de la nouvelle
     * taxonomie : [slug produit => slug sous-catégorie].
     */
    public static function productMap(): array
    {
        return [
            // Impression Papier & Bureautique
            'carte-de-visite-classique'        => 'cartes-de-visite',
            'carte-de-visite-premium'         => 'cartes-de-visite',
            'carte-de-visite-pvc'             => 'cartes-de-visite',
            'flyer-a5'                        => 'flyers-prospectus',
            'flyer-a4'                        => 'flyers-prospectus',
            'depliant-2-volets-a5'            => 'depliants',
            'brochure-a4-8-pages'             => 'brochures-catalogues',
            'catalogue-a4-16-pages'           => 'brochures-catalogues',
            'brochure-a5-12-pages'            => 'brochures-catalogues',
            'affiche-a2'                      => 'affiches-posters',
            'affiche-a3'                      => 'affiches-posters',
            'papier-a-en-tete-a4'             => 'papier-a-en-tete',
            'bloc-notes-personnalise'         => 'blocs-notes-carnets',
            'carnet-couverture-rigide'        => 'blocs-notes-carnets',
            'planner-personnalise'            => 'calendriers-agendas',
            'calendrier-mural'                => 'calendriers-agendas',
            'calendrier-de-bureau'            => 'calendriers-agendas',
            'calendrier-illustre'             => 'calendriers-agendas',
            'menu-de-restaurant'              => 'menus-de-restaurant',
            'set-de-table-publicitaire'       => 'impression-de-documents',
            'reprographie-plans'              => 'impression-de-documents',

            // Impression Grand Format
            'roll-up-85x200'                  => 'roll-ups-kakemonos',
            'mini-rollup'                     => 'roll-ups-kakemonos',
            'sacoche-transport-rollup'        => 'roll-ups-kakemonos',
            'bache-vinyle-m2'                 => 'baches-publicitaires',
            'vinyle-one-way-vision'           => 'vinyles-microperfores',
            'adhesif-vinyle-blanc-vitrine'    => 'habillage-de-vitrines',
            'vitrophanie-decorative'          => 'habillage-de-vitrines',
            'adhesif-vitrine-promotionnel'    => 'habillage-de-vitrines',
            'vinyle-depoli-vitrine'           => 'habillage-de-vitrines',
            'panneau-forex'                   => 'impression-sur-pvc-forex',

            // Packaging & Emballage
            'etiquettes-adhesives-rondes'     => 'etiquettes-adhesives-en-rouleaux',
            'stickers-decouples-forme'        => 'stickers-packaging',
            'stickers-transparents'           => 'stickers-packaging',
            'sac-papier-kraft'                => 'sacs-kraft-personnalises',
            'boite-carton-personnalisee'      => 'boites-personnalisees',

            // Signalétique & Enseignes
            'lettres-3d-decoupees'            => 'lettres-3d',
            'lettres-lumineuses-led'          => 'enseignes-lumineuses',
            'plaque-plexiglass-noire'         => 'plaques-professionnelles',
            'plaque-plexiglass-transparente'  => 'plaques-professionnelles',
            'plaque-plexiglass-entretoises'   => 'plaques-professionnelles',

            // Objets Publicitaires & Cadeaux
            'stylo-publicitaire'              => 'stylos-publicitaires',
            'mug-personnalise'                => 'mugs-personnalises',
            'bouteille-sport-personnalisee'   => 'gourdes-bouteilles',
            'cle-usb-publicitaire'            => 'cles-usb-personnalisees',

            // Impression Textile & Personnalisation
            't-shirt-personnalise'            => 't-shirts-personnalises',

            // Impression Événementielle
            'x-banner-80x180'                 => 'x-banners-l-banners',
            'mini-roll-up-de-table'           => 'roll-ups-evenementiels',
            'banniere-sac-a-dos'              => 'drapeaux-publicitaires',
        ];
    }
}
