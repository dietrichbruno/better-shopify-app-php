#!/usr/bin/.env bash
node url.mjs
docker-compose -f ./docker-compose.yml exec -T php-fpm php artisan config:clear
npm run build
npm run shopify app update-url