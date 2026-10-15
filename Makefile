# Makefile pour faciliter les opérations locales

.PHONY: up down init install-assets migrate seed logs shell composer node

up:
	docker compose up -d

down:
	docker compose down --volumes

# Initialise le projet Laravel dans le conteneur PHP (exécuter uniquement si vous acceptez)
init:
	docker compose exec app bash -lc "if [ ! -f artisan ]; then composer create-project laravel/laravel . --prefer-dist; fi; cp .env.example .env || true; php artisan key:generate || true;"

install-assets:
	docker compose exec node sh -c "npm install && npm run dev"

migrate:
	docker compose exec app bash -lc "php artisan migrate --force"

seed:
	docker compose exec app bash -lc "php artisan db:seed --force"

logs:
	docker compose logs -f

shell:
	docker compose exec app bash

composer:
	docker compose exec app composer $(ARGS)

node:
	docker compose exec node sh -c "npm $(ARGS)"
