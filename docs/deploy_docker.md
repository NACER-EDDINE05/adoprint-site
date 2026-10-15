# Déploiement local rapide avec Docker (one-click)

But: automatiser la préparation du projet pour obtenir une URL prête (http://localhost:8080) sans exécuter manuellement chaque commande.

Prérequis : Docker Desktop (Windows), Docker Compose installé.

Étapes :
1. Ouvrez PowerShell à la racine du projet.
2. Lancez le script one-click :

```powershell
.\scripts\one_click_setup.ps1
```

Le script effectue :
- `docker-compose up -d --build`
- attend la base de données
- copie `.env.example` -> `.env` et fixe `APP_URL` sur `http://localhost:8080`
- `composer install`
- `php artisan key:generate`
- `php artisan migrate --force`
- `php artisan db:seed --class=SuperAdminSeeder`
- `npm install && npm run build` dans un conteneur `node`
- crée le lien `storage:link`

Après succès, ouvrez :

- Site : http://localhost:8080
- Admin Filament (si installé) : http://localhost:8080/admin

Identifiants admin (seed) :
- Email : admin@imprimerie.local
- Mot de passe : password123

Remarques :
- Si vous préférez ne pas utiliser Docker, suivez les étapes manuelles dans `README.md`.
- Le script écrit dans `.env` la valeur `APP_URL` par défaut. Modifiez si vous avez un domaine ou un vhost.

Sécurité : changez le mot de passe admin après la première connexion.
