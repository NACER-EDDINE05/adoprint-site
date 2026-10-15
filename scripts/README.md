Scripts disponibles

- `init-laravel.sh` : initialise Laravel et installe dépendances recommandées (exécuter dans le conteneur `app`).

Usage :
```bash
# Démarrer les services
make up
# Puis lancer le script d'initialisation (dans l'hôte)
make init
# Ou plus directement :
# docker compose exec app bash /var/www/html/scripts/init-laravel.sh
```
