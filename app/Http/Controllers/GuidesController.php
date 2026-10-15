<?php

namespace App\Http\Controllers;

/**
 * Ressources & conseils : guides pratiques d'impression.
 * Contenu pédagogique statique et factuel (préparation de fichiers, papiers, colorimétrie).
 */
class GuidesController extends Controller
{
    public function index()
    {
        return view('guides', ['guides' => $this->all()]);
    }

    public function show(string $slug)
    {
        $guide = collect($this->all())->firstWhere('slug', $slug);
        abort_if(! $guide, 404);

        return view('guide', ['guide' => $guide]);
    }

    private function all(): array
    {
        return [
            [
                'slug'    => 'preparer-fichier',
                'icon'    => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/>',
                'title'   => 'Préparer votre fichier d\'impression',
                'excerpt' => 'PDF 300 dpi, CMJN, fonds perdus 3 mm : le checklist complet pour un fichier accepté du premier coup.',
                'intro'   => 'Un fichier bien préparé garantit une impression conforme, sans échange ni retard. Voici les règles que notre équipe de production applique.',
                'sections' => [
                    ['Format du fichier', 'Le PDF haute définition est le format idéal. Nous acceptons également les images PNG et JPG (300 dpi minimum) ainsi que les fichiers AI et EPS.'],
                    ['Résolution', 'Comptez 300 dpi pour les supports de proximité (cartes, flyers, brochures) et 100 à 150 dpi pour les grands formats (bâches, roll-up) vus à distance.'],
                    ['Fonds perdus', 'Prévoyez 3 mm de fond perdu sur chaque bord et gardez vos textes à 5 mm minimum du bord de coupe.'],
                    ['Colorimétrie', 'Travaillez en CMJN. Les fichiers RVB sont convertis automatiquement, avec de légers décalages possibles sur les couleurs vives.'],
                    ['Polices', 'Vectorisez vos polices ou intégrez-les dans le PDF pour éviter toute substitution de caractère.'],
                ],
            ],
            [
                'slug'    => 'choisir-papier',
                'icon'    => '<path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20"/>',
                'title'   => 'Bien choisir son papier',
                'excerpt' => 'Couché, offset, recyclé : quel grammage pour quel usage ? Le guide pratique des papiers d\'impression.',
                'intro'   => 'Le choix du papier change le rendu, le toucher et le coût final. Ce guide vous aide à trancher selon l\'usage réel de votre support.',
                'sections' => [
                    ['Couché brillant (135 à 350 g)', 'Le polyvalent des supports marketing : couleurs éclatantes, rendu net. Idéal pour flyers, dépliants et affiches.'],
                    ['Couché mat (250 à 350 g)', 'Rendu plus sobre, anti-reflets, agréable en main. Recommandé pour cartes de visite et brochures premium.'],
                    ['Offset (80 à 120 g)', 'Le papier des courriers et papiers à en-tête : blanc naturel, compatible imprimantes de bureau et tampons.'],
                    ['Recyclé', 'Aspect légèrement grisé et toucher fibre, pour une image écologique assumée (sacs kraft, flyers éco-responsables).'],
                    ['Grammage en résumé', '80–120 g : courrier · 135–170 g : flyers · 250–350 g : cartes et couvertures · 400 g+ : cartes premium.'],
                ],
            ],
            [
                'slug'    => 'cmjn-rvb',
                'icon'    => '<circle cx="13.5" cy="6.5" r=".5"/><circle cx="17.5" cy="10.5" r=".5"/><circle cx="8.5" cy="7.5" r=".5"/><circle cx="6.5" cy="12.5" r=".5"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"/>',
                'title'   => 'CMJN ou RVB ?',
                'excerpt' => 'Pourquoi vos couleurs changent à l\'impression, et comment préparer vos fichiers pour un rendu fidèle.',
                'intro'   => 'Les écrans affichent en RVB (lumière), les presses impriment en CMJN (encres). La conversion est automatique mais peut décaler certaines teintes.',
                'sections' => [
                    ['Deux modes, deux usages', 'RVB : écrans (site web, réseaux sociaux). CMJN : impression. Un même fichier ne peut pas être optimal pour les deux.'],
                    ['Pourquoi la conversion décale les couleurs', 'Le RVB couvre une gamme plus large que le CMJN. Les oranges, verts néon et bleus électriques sortent rarement tels quels à l\'impression.'],
                    ['Nos recommandations', 'Livrez vos fichiers en CMJN, FOGRA39 de préférence, et demandez-nous un épreuvage numérique pour les projets sensibles à la couleur.'],
                    ['Le cas des aplats', 'Pour une couleur de marque précise, fournissez la référence Pantone ou la valeur CMJN exacte : nous calons la presse dessus.'],
                ],
            ],
        ];
    }
}
