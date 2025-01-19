#!/bin/bash

# Install PHP dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader

# Clear and cache config and routes
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache

# Install NPM dependencies and build assets
npm install
npm run build 