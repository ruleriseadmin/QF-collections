#!/usr/bin/env bash

# Pull the application image
docker compose -f docker-compose.production.yml pull collection_service_app || true

# Kill the running containers
docker compose -f docker-compose.production.yml down --remove-orphans

# Restart containers (single instance; scaling breaks fixed upstreams)
docker compose -f docker-compose.production.yml up -d

# Wait for app container to be healthy/ready
sleep 8

# Clear all old application configuration cache
docker compose -f docker-compose.production.yml exec -T collection_service_app php artisan optimize:clear || true

# Cache application with new configuration
# docker-compose -f docker-compose.production.yml exec app php artisan optimize

# Run migrations
docker compose -f docker-compose.production.yml exec -T collection_service_app php artisan migrate --force || true

# Run commands
docker compose -f docker-compose.production.yml exec -T collection_service_app php artisan schedule:run
