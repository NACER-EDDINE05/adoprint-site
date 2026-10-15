# Extraction et complétion des traductions Filament

But: expliquer comment copier les fichiers de traduction fournis par Filament (après `composer install`) et générer les fichiers `resources/lang/*/filament.php`.

Méthode simple (recommandée après `composer install`) :

1. Exécuter le script fourni :

```powershell
# depuis la racine du projet
php scripts/copy_filament_translations.php
```

Ce script cherchera les fichiers `filament.php` fournis par les paquets Filament dans `vendor/` et copiera chaque fichier trouvé vers `resources/lang/<locale>/filament.php`.

2. Réviser et compléter les traductions copiées dans `resources/lang/<locale>/filament.php`.

3. Pour générer automatiquement un fichier `filament.php` à partir des clés trouvées dans `vendor/filament/*/lang/*`, vous pouvez utiliser le script ci-dessus comme point de départ et l'étendre pour fusionner ou préserver les traductions existantes.

4. Après modification, rebuild des assets si vous ajoutez des classes CSS spécifiques :

```powershell
npm run build
```

Notes :
- Si `vendor/filament` n'existe pas encore, exécutez `composer require filament/filament filament/tables livewire/livewire` localement.
- L'agent a ajouté des clés courantes FR/AR dans `resources/lang/fr/filament.php` et `resources/lang/ar/filament.php` comme point de départ.

## Commande Artisan

Un raccourci Artisan a été ajouté pour exécuter le générateur depuis la CLI Laravel. Il enveloppe le script `scripts/generate_filament_translations.php` et supporte l'option `--write`.

Usage de base (génère les fichiers fusionnés sans écraser les originaux) :

```powershell
php artisan filament:translations:generate
```

Après revue, appliquez les changements aux vrais fichiers `filament.php` (un backup sera créé) :

```powershell
php artisan filament:translations:generate --write
```

Remarques :
- Exécutez `composer install` avant d'utiliser la commande pour que les paquets Filament soient présents dans `vendor/`.
- La commande écrit par défaut dans `resources/lang/<locale>/filament.generated.php` pour éviter d'écraser vos traductions existantes. Utilisez `--write` pour mettre à jour `filament.php`.

Vérifiez et ajustez les traductions générées avant d'utiliser `--write`.
