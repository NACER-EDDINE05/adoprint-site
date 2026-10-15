# Guide de traduction Filament

Objectif: expliquer comment traduire l'interface Filament (menus, labels, boutons, messages) et intégrer des traductions FR/AR dans ce projet.

1) Emplacement des fichiers
- Créez des fichiers de traduction dans `resources/lang/{locale}/filament.php`.
- Nous avons ajouté des exemples: `resources/lang/fr/filament.php` et `resources/lang/ar/filament.php`.

2) Clés recommandées
- `navigation.administration` — groupe de navigation
- `resources.{resource_key}.label` — label d'une ressource
- `buttons.create|edit|delete|save|cancel|confirm` — boutons courants
- `table.actions|empty` — textes des tables
- `form.required` — messages de validation simples
- `messages.saved|deleted` — feedback utilisateur

3) Intégration dans Filament
- Dans les Resources Filament, utilisez `__('filament.resources.products.label')` pour labels.
- Pour le groupe de navigation, utilisez `getNavigationGroup()` qui retourne `__('filament.navigation.administration')`.

4) Activer la langue
- Pour tester, changez `app.locale` dans `config/app.php` ou au runtime:
```php
config(['app.locale' => 'ar']);
```
- Filament respecte `app.locale` par défaut. Vous pouvez forcer `locale` dans `config/filament.php` si besoin.

5) RTL & styles
- Pour l'arabe (RTL), ajoutez `dir="rtl"` sur les conteneurs ou utilisez une classe utilitaire (Tailwind) pour `direction: rtl`.
- Filament ne compile pas automatiquement RTL CSS; vous pouvez ajouter des overrides CSS pour l'interface admin si nécessaire.

6) Publication et surcharge des traductions Filament
- Filament fournit traductions via son package. Pour les surcharger, créez vos clés localement (`resources/lang/{locale}/filament.php`) — Laravel cherchera d'abord ces fichiers.

7) Exemple rapide
```php
// ProductResource.php
public static function getNavigationLabel(): string
{
    return __('filament.resources.products.label');
}
```

8) Processus conseillé pour traduction complète
- Extraire toutes les phrases Filament via inspection visuelle ou en parcourant `vendor/filament`.
- Compléter les fichiers `resources/lang/{locale}/filament.php` avec toutes les clés nécessaires.
- Tester en changeant `app.locale` et parcourir l'admin.

9) Aide
- Si vous voulez, je peux:
  - parcourir `vendor/filament` et générer automatiquement un starter `filament.php` complet,
  - ou ajouter des overrides CSS pour RTL et exemples Tailwind.

*** End of guide ***
