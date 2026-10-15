# Intégration RTL pour Filament (admin)

But: fournir des instructions claires pour activer l'interface administrateur Filament en RTL (arabe).

Étapes recommandées (localement) :

1. Installer les dépendances (exécutez localement) :

```powershell
Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass -Force
$env:Path = 'C:\xampp\php;' + $env:Path
composer install
npm install
```

2. Importer le CSS RTL (nous avons ajouté `resources/css/filament-rtl.css` et l'avons importé depuis `resources/css/app.css`).

3. Dans vos templates Blade d'admin (généralement `resources/views/layouts/app.blade.php` ou le layout de Filament), ajoutez l'attribut `dir` selon la locale :

```blade
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
```

4. Build des assets :

```powershell
npm run build
```

5. Vérifier l'admin Filament. Après l'installation de Filament, affinez `resources/css/filament-rtl.css` pour cibler les bons sélecteurs DOM et ajuster les icônes.

Conseils :
- Préférez les propriétés CSS logiques (`margin-inline-start`, `text-align: start`) dans vos styles pour minimiser les règles spécifiques.
- Si vous utilisez Tailwind, activez ou créez un variant `dir` pour cibler `[dir="rtl"]`.

