# ADO PRINT — Prompts de génération des photos produits

## Règles communes (à préfixer sur CHAQUE prompt)

```
Professional e-commerce product photo for an online printing company.
Studio lighting, soft natural shadows, clean light-gray seamless background.
Photorealistic, sharp focus, high-end catalog quality.
The printed design must be ABSTRACT placeholder artwork (geometric shapes, gradients) — NO readable text, NO logos, NO letters.
Landscape 4:3 — output 1200 x 900 px.
```

> Astuce : générer d'abord 1 produit, valider le style, puis générer les 21 autres
> avec le même préfixe pour garder une bibliothèque cohérente.

## Emplacements (dimensions techniques)

| Emplacement sur le site | Ratio | Pixels recommandés |
|---|---|---|
| Carte produit (grille, promos, phares) | 4:3 paysage | **1200 × 900** |
| Page produit (grand visuel) | 4:3 paysage | 1200 × 900 (le même fichier) |
| Fond du héros de l'accueil (option) | 16:9 paysage | 1920 × 1080 |

Les visuels sont recadrés en `object-cover` : tout fichier 4:3 fonctionne parfaitement.

## Les 22 produits

### Cartes de visite
1. **Carte de visite classique** — `carte-de-visite-classique`
   `A neat stack of classic matte business cards, 85 x 54 mm, fanned slightly on a surface, white 300g cardstock with a subtle abstract cyan-magenta-yellow diagonal stripe design on one card face.`
2. **Carte de visite premium** — `carte-de-visite-premium`
   `Premium thick business cards with soft-touch matte lamination and spot UV gloss, elegant dark card with a subtle abstract geometric emblem, stack with one card leaning, shallow depth of field.`
3. **Carte de visite PVC** — `carte-de-visite-pvc`
   `Plastic PVC business cards, glossy white plastic 0.76 mm thick, one translucent frosted card held against light, modern abstract colorful design, water droplets on surface to show resistance.`

### Flyers & dépliants
4. **Flyer A5** — `flyer-a5`
   `A5 flyers (148 x 210 mm) in a neat spread fan on a table, glossy 135g paper, vibrant abstract gradient print in cyan magenta and yellow.`
5. **Flyer A4** — `flyer-a4`
   `A4 flyers (210 x 297 mm) stacked in small piles, full-color abstract print, one flyer slightly lifted showing gloss finish.`
6. **Dépliant 2 volets A5** — `depliant-2-volets-A5`
   `A bi-fold leaflet opened to show inner panels next to a closed one, A4 folded to A5, 170g silk paper, abstract color blocks across panels.`

### Brochures & catalogues
7. **Brochure A4 · 8 pages** — `brochure-a4-8-pages`
   `A4 saddle-stitched brochure, 8 pages, slightly open showing inner spreads, glossy abstract layout, clean desk scene.`
8. **Catalogue A4 · 16 pages** — `catalogue-a4-16-pages`
   `Thick A4 perfect-bound catalogue, 16 pages, standing upright with cover facing camera plus one open copy behind, abstract colorful cover art.`
9. **Brochure A5 · 12 pages** — `brochure-a5-12-pages`
   `A5 perfect-bound brochure, 12 pages, stacked with one open on top, matte cover with abstract minimalist design.`

### Affiches & posters
10. **Affiche A2** — `affiche-a2`
    `Large A2 poster (420 x 594 mm) mounted on a wall, bold abstract gradient artwork in brand cyan magenta yellow, slight side angle.`
11. **Affiche A3** — `affiche-a3`
    `A3 posters (297 x 420 mm), one taped on a wall and two leaning against it, vivid abstract print, gallery-like white wall.`

### Étiquettes & stickers
12. **Étiquettes adhésives rondes** — `etiquettes-adhesives-rondes`
    `Round white adhesive labels in various diameters on a sheet and peeled ones stuck on plain product jars, clean blank label centers with thin colored ring borders.`
13. **Stickers découpés à la forme** — `stickers-decouples-forme`
    `Die-cut vinyl stickers in custom shapes (star, leaf, abstract blob) with contour cut white border, peeled corner showing adhesive, vivid colors.`

### Papeterie d'entreprise
14. **Papier à en-tête A4** — `papier-a-en-tete-a4`
    `A4 letterhead sheets, 90g offset paper, subtle abstract header band in brand colors at top of otherwise blank page, neat stack with one sheet flat.`
15. **Bloc-notes personnalisé** — `bloc-notes-personnalise`
    `A5 notepad with 300g cardboard cover printed with abstract design, 50 sheets, pencil resting on top, desk scene.`

### Objets publicitaires
16. **Stylo publicitaire** — `stylo-publicitaire`
    `Group of promotional ballpoint pens in brand cyan with silver accents, one pen isolated in foreground on white surface.`
17. **Mug personnalisé** — `mug-personnalise`
    `White ceramic 330 ml mug with full-color wrap print of abstract ink swirls, coffee-scene prop, soft window light.`
18. **T-shirt personnalisé** — `t-shirt-personnalise`
    `Folded and one hanging cotton t-shirt (180g) with large abstract screen-printed artwork across chest, neutral gray background.`

### Grand format & PLV
19. **Roll-up 85 × 200 cm** — `roll-up-85x200`
    `Aluminium roll-up banner stand 85 x 200 cm fully deployed with abstract vertical gradient print, standing in a bright exhibition corner, full product visible.`
20. **Bâche vinyle (par m²)** — `bache-vinyle-m2`
    `Large white PVC banner with eyelets every 50 cm along edges, abstract colorful print, stretched between two posts outdoors, slight perspective.`

### Packaging
21. **Sac papier kraft** — `sac-papier-kraft`
    `Kraft paper shopping bags with twisted handles in two sizes, one with minimal abstract stamp-style print, studio scene.`
22. **Boîte carton personnalisée** — `boite-carton-personnalisee`
    `Custom printed cardboard box (compact 400g board) with abstract two-tone print, lid slightly open, clean white scene.`

## Fond du héros (optionnel, 1920 × 1080)

```
Wide-angle photo of a modern print shop floor: large format printers, paper
stacks on pallets, work tables with colorful printed brochures, one operator
at a cutting table. Bright industrial daylight, clean and organized,
photorealistic, no readable text anywhere. Landscape 16:9 — 1920 x 1080 px.
```
