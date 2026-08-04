#!/bin/bash
# Deploy update form berita ke VPS
set -e

cd /var/www/uptd-kalibrasi

echo ">>> Pull latest code..."
git pull origin feature/push-current

echo ">>> Run migration..."
php artisan migrate --force

echo ">>> Clear cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo ">>> Done! Form berita sudah diperbarui."
