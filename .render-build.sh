#!/usr/bin/env bash
set -eux

# Install Composer dependencies
composer install --optimize-autoloader --no-dev

# Clear caches (optional but recommended)
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Run database migrations (if needed)
php artisan migrate --force
