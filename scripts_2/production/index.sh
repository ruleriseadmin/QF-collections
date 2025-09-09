#!/usr/bin/env bash

# Pull a new application instance
docker compose -f docker-collection-compose.production.yml pull collection_app

# Kill the running containers
docker compose -f docker-collection-compose.production.yml down --remove-orphans

# Restart containers
docker compose -f docker-collection-compose.production.yml up -d --scale collection_app=2 -d

# Clear all old application configuration cache
docker compose -f docker-collection-compose.production.yml exec collection_app php artisan optimize:clear

# Cache application with new configuration
# docker-compose -f docker-compose.production.yml exec app php artisan optimize

# Run migrations
docker compose -f docker-collection-compose.production.yml exec collection_app php artisan migrate --force

# Run commands
docker compose -f docker-collection-compose.production.yml exec -T collection_app php artisan schedule:run
