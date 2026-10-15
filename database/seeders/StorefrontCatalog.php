<?php

namespace Database\Seeders;

/**
 * Données de catalogue pour la boutique publique ADO PRINT
 * (prix unitaires dégressifs en MAD — contenu original ADO PRINT).
 */
class StorefrontCatalog
{
    public static function categories(): array
    {
        return [
            ['name' => 'Cartes de visite', 'slug' => 'cartes-de-visite', 'description' => 'Cartes de visite papier et plastique, tous formats et finitions : vernis sélectif, pelliculage mat ou brillant.'],
            ['name' => 'Flyers & dépliants', 'slug' => 'flyers-depliants', 'description' => 'Flyers A6 à A4 et dépliants 2 à 3 volets, papiers couchés ou recyclés, quadri recto/verso.'],
            ['name' => 'Brochures & catalogues', 'slug' => 'brochures-catalogues', 'description' => 'Brochures, catalogues et magazines dos carré collé ou agrafé, à partir de 8 pages.'],
            ['name' => 'Affiches & posters', 'slug' => 'affiches-posters', 'description' => 'Affiches publicitaires du A4 au A0, impression haute définition sur papier photo ou couché.'],
            ['name' => 'Étiquettes & stickers', 'slug' => 'etiquettes-stickers', 'description' => 'Étiquettes adhésives et stickers découpés à la forme, rouleaux ou planches.'],
            ['name' => "Papeterie d'entreprise", 'slug' => 'papeterie-entreprise', 'description' => "Papiers à en-tête, enveloppes, blocs-notes et tampons à l'effigie de votre société."],
            ['name' => 'Objets publicitaires', 'slug' => 'objets-publicitaires', 'description' => 'Stylos, mugs, t-shirts et goodies personnalisés à votre logo.'],
            ['name' => 'Grand format & PLV', 'slug' => 'grand-format-plv', 'description' => 'Roll-up, kakemonos, bâches vinyle et panneaux : idéal pour salons et événements.'],
            ['name' => 'Packaging', 'slug' => 'packaging', 'description' => 'Sacs papier, boîtes carton personnalisées et emballages sur mesure pour votre marque.'],
        ];
    }

    public static function products(): array
    {
        return array_merge(self::productsA(), self::productsB());
    }

    private static function productsA(): array
    {
        return [
            ['slug' => 'carte-de-visite-classique', 'category' => 'cartes-de-visite', 'name' => 'Carte de visite classique',
             'description' => "Carte de visite 85 × 54 mm sur papier couché 300 g, impression quadri recto.\nLa référence pour démarrer avec une image professionnelle.",
             'grids' => [[100, 300, 1.20], [301, 1000, 0.95], [1001, 5000, 0.75], [5001, 10000, 0.60]]],
            ['slug' => 'carte-de-visite-premium', 'category' => 'cartes-de-visite', 'name' => 'Carte de visite premium',
             'description' => "Papier 350 g avec pelliculage mat et vernis sélectif au recto.\nUn toucher doux et un rendu haut de gamme pour vous démarquer.",
             'grids' => [[100, 300, 1.80], [301, 1000, 1.40], [1001, 5000, 1.10], [5001, 10000, 0.90]]],
            ['slug' => 'carte-de-visite-pvc', 'category' => 'cartes-de-visite', 'name' => 'Carte de visite PVC',
             'description' => "Carte en PVC 0,76 mm, résistante à l'eau et à l'usure.\nIdéale pour les badges clients, cartes de fidélité et accès.",
             'grids' => [[50, 200, 4.50], [201, 500, 3.60], [501, 2000, 2.90], [2001, 5000, 2.40]]],
            ['slug' => 'flyer-a5', 'category' => 'flyers-depliants', 'name' => 'Flyer A5',
             'description' => "Flyer A5 (148 × 210 mm) sur couché 135 g brillant, quadri recto.\nLe format polyvalent pour vos promotions et événements.",
             'grids' => [[250, 999, 0.90], [1000, 2499, 0.68], [2500, 4999, 0.45], [5000, 20000, 0.34]]],
            ['slug' => 'flyer-a4', 'category' => 'flyers-depliants', 'name' => 'Flyer A4',
             'description' => "Flyer A4 (210 × 297 mm) sur couché 135 g brillant, quadri recto.\nUn format généreux pour une communication détaillée.",
             'grids' => [[250, 999, 1.40], [1000, 2499, 1.05], [2500, 4999, 0.72], [5000, 20000, 0.55]]],
            ['slug' => 'depliant-2-volets-a5', 'category' => 'flyers-depliants', 'name' => 'Dépliant 2 volets A5',
             'description' => "Dépliant A4 fermé en A5, papier couché 170 g, quadri recto/verso.\nSix faces pour présenter votre activité avec clarté.",
             'grids' => [[250, 999, 1.60], [1000, 2499, 1.25], [2500, 4999, 0.95], [5000, 20000, 0.75]]],
            ['slug' => 'brochure-a4-8-pages', 'category' => 'brochures-catalogues', 'name' => 'Brochure A4 · 8 pages',
             'description' => "Brochure 8 pages dos agrafé, intérieur couché 115 g, couverture 250 g.\nParfaite pour vos plaques commerciales.",
             'grids' => [[50, 199, 9.50], [200, 499, 7.20], [500, 999, 5.40], [1000, 5000, 4.20]]],
            ['slug' => 'catalogue-a4-16-pages', 'category' => 'brochures-catalogues', 'name' => 'Catalogue A4 · 16 pages',
             'description' => "Catalogue 16 pages dos carré collé, intérieur couché 115 g, couverture 300 g pelliculée.\nPour présenter toute votre gamme en beauté.",
             'grids' => [[50, 199, 16.00], [200, 499, 12.50], [500, 999, 9.80], [1000, 5000, 7.90]]],
            ['slug' => 'affiche-a2', 'category' => 'affiches-posters', 'name' => 'Affiche A2',
             'description' => "Affiche 420 × 594 mm sur papier couché 135 g, impression HD.\nDynamisez vos vitrines et vos campagnes en intérieur.",
             'grids' => [[10, 49, 12.00], [50, 199, 9.00], [200, 499, 6.50], [500, 2000, 4.90]]],
            ['slug' => 'affiche-a3', 'category' => 'affiches-posters', 'name' => 'Affiche A3',
             'description' => "Affiche 297 × 420 mm sur couché 135 g, impression haute définition.\nLe format le plus polyvalent pour l'affichage en point de vente.",
             'grids' => [[10, 49, 7.50], [50, 199, 5.60], [200, 499, 4.10], [500, 2000, 3.20]]],
        ];
    }

    private static function productsB(): array
    {
        return [
            ['slug' => 'etiquettes-adhesives-rondes', 'category' => 'etiquettes-stickers', 'name' => 'Étiquettes adhésives rondes',
             'description' => "Étiquettes adhésives papier blanches, découpe ronde au diamètre de votre choix.\nConditionnées en rouleaux prêtes à appliquer.",
             'grids' => [[250, 999, 0.45], [1000, 2499, 0.32], [2500, 4999, 0.24], [5000, 20000, 0.18]]],
            ['slug' => 'stickers-decouples-forme', 'category' => 'etiquettes-stickers', 'name' => 'Stickers découpés à la forme',
             'description' => "Stickers vinyle découpés au contour de votre logo, résistants en extérieur.\nIdéaux pour véhicules, vitrines et produits.",
             'grids' => [[50, 199, 2.80], [200, 499, 2.10], [500, 999, 1.50], [1000, 5000, 1.10]]],
            ['slug' => 'papier-a-en-tete-a4', 'category' => 'papeterie-entreprise', 'name' => 'Papier à en-tête A4',
             'description' => "Papier à en-tête A4 offset 90 g, impression quadri ou bichromie.\nDonnez une image soignée à tous vos courriers.",
             'grids' => [[250, 999, 0.85], [1000, 2499, 0.62], [2500, 4999, 0.48], [5000, 20000, 0.38]]],
            ['slug' => 'bloc-notes-personnalise', 'category' => 'papeterie-entreprise', 'name' => 'Bloc-notes personnalisé',
             'description' => "Bloc-notes A5, 50 feuilles, couverture carton 300 g personnalisée.\nUn cadeau d'entreprise utile et toujours visible.",
             'grids' => [[50, 199, 6.50], [200, 499, 4.90], [500, 999, 3.60], [1000, 5000, 2.80]]],
            ['slug' => 'stylo-publicitaire', 'category' => 'objets-publicitaires', 'name' => 'Stylo publicitaire',
             'description' => "Stylo bille personnalisé à votre logo, encre noire, livré par boîte de 100.\nLe support publicitaire le plus distribué au monde.",
             'grids' => [[100, 499, 3.20], [500, 999, 2.60], [1000, 2499, 2.10], [2500, 10000, 1.70]]],
            ['slug' => 'mug-personnalise', 'category' => 'objets-publicitaires', 'name' => 'Mug personnalisé',
             'description' => "Mug céramique 330 ml, impression haute intensité lavable au lave-vaisselle.\nOffert ou vendu, il fait toujours son effet.",
             'grids' => [[24, 99, 28.00], [100, 249, 22.00], [250, 499, 18.00], [500, 2000, 15.00]]],
            ['slug' => 't-shirt-personnalise', 'category' => 'objets-publicitaires', 'name' => 'T-shirt personnalisé',
             'description' => "T-shirt coton 180 g, impression sérigraphie ou DTF, toutes tailles.\nPour vos équipes, vos événements et vos clients.",
             'grids' => [[10, 49, 45.00], [50, 99, 38.00], [100, 249, 32.00], [250, 1000, 28.00]]],
            ['slug' => 'roll-up-85x200', 'category' => 'grand-format-plv', 'name' => 'Roll-up 85 × 200 cm',
             'description' => "Roll-up avec structure aluminium et toile 510 g, housse de transport incluse.\nMontage en 30 secondes, parfait pour salons et accueil.",
             'grids' => [[1, 4, 480.00], [5, 9, 420.00], [10, 24, 380.00], [25, 100, 340.00]]],
            ['slug' => 'bache-vinyle-m2', 'category' => 'grand-format-plv', 'name' => 'Bâche vinyle (par m²)',
             'description' => "Bâche vinyle 440 g avec œillets tous les 50 cm, impression UV haute densité.\nRésistante aux intempéries pour une communication durable.",
             'grids' => [[1, 9, 90.00], [10, 29, 78.00], [30, 99, 68.00], [100, 500, 58.00]]],
            ['slug' => 'sac-papier-kraft', 'category' => 'packaging', 'name' => 'Sac papier kraft',
             'description' => "Sac kraft personnalisable, poignées torsadées, plusieurs dimensions disponibles.\nUne image écologique pour votre boutique.",
             'grids' => [[100, 499, 4.20], [500, 999, 3.30], [1000, 2499, 2.60], [2500, 10000, 2.10]]],
            ['slug' => 'boite-carton-personnalisee', 'category' => 'packaging', 'name' => 'Boîte carton personnalisée',
             'description' => "Boîte en carton compact 400 g, impression quadri ou bichromie, montage facile.\nSublimez vos produits et vos envois.",
             'grids' => [[50, 199, 8.50], [200, 499, 6.60], [500, 999, 5.20], [1000, 5000, 4.10]]],
            ['slug' => 'brochure-a5-12-pages', 'category' => 'brochures-catalogues', 'name' => 'Brochure A5 · 12 pages',
             'description' => "Brochure A5 12 pages dos carré collé, intérieur couché 115 g.\nUn format compact et économique pour vos présentations.",
             'grids' => [[50, 199, 8.20], [200, 499, 6.20], [500, 999, 4.70], [1000, 5000, 3.60]]],
        ];
    }
}
