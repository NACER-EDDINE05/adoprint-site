#!/usr/bin/env bash
set -e

# Script à exécuter dans le conteneur 'app' pour initialiser Laravel.
# Usage (depuis l'hôte): docker compose exec app bash /var/www/html/scripts/init-laravel.sh

if [ -f artisan ]; then
  echo "Laravel semble déjà présent — sortie."
  exit 0
fi

echo "Création du projet Laravel..."
composer create-project laravel/laravel . --prefer-dist --no-interaction

echo "Copie .env.example -> .env"
cp .env.example .env

# Ajuster la configuration DB pour l'environnement Docker
sed -i "s/DB_HOST=127.0.0.1/DB_HOST=db/g" .env || true
sed -i "s/DB_DATABASE=laravel/DB_DATABASE=ado_print/g" .env || true
sed -i "s/DB_USERNAME=root/DB_USERNAME=ado_user/g" .env || true
sed -i "s/DB_PASSWORD=/DB_PASSWORD=secret/g" .env || true

php artisan key:generate

# Installer dépendances recommandées et dev
composer require livewire/livewire filament/filament spatie/laravel-permission spatie/laravel-activitylog astrotomic/laravel-translatable || true
composer require --dev pestphp/pest --with-all-dependencies || true

# Installer assets
npm install || true
npm run dev || true

# Migrations initiales
php artisan migrate --force || true
php artisan db:seed --force || true

echo "Initialisation terminée."
