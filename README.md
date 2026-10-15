# Plateforme d'imprimerie — Scaffold initial

Ce dépôt contiendra la plateforme d'imprimerie en ligne (MVP).

Prérequis locaux
- PHP 8.2+ (8.3 recommandé)
- Composer
- MySQL 8 (ou MariaDB)
- Node.js + npm
- XAMPP ou environnement équivalent (ou Docker)

Configuration rapide (XAMPP)
```bash
cd "c:\xampp\htdocs\ADO PRINT SITE"
# Installer Laravel dans ce dossier (si vous voulez que l'agent le créé automatiquement)
composer create-project laravel/laravel . --prefer-dist
cp .env.example .env
# éditer .env pour config DB
php artisan key:generate
npm install && npm run dev
php artisan migrate --seed
php artisan serve --host=127.0.0.1 --port=8000
```

Dépendances recommandées (à installer après création du projet)
```bash
composer require livewire/livewire filament/filament spatie/laravel-permission spatie/laravel-activitylog astrotomic/laravel-translatable
composer require --dev pestphp/pest --with-all-dependencies
npm install
```

Notes
- Les données commerciales (nom, logo, contacts, prix...) seront configurables depuis le dashboard.
- Le fichier `Cahier_des_charges_plateforme_imprimerie.md` dans vos téléchargements était vide; je travaille d'après le cahier des charges transmis dans la conversation.

Prochaine étape
- Exécuter l'initialisation Laravel (composer create-project) ou me dire si vous préférez Docker. Je peux lancer l'installation si vous me l'autorisez.
