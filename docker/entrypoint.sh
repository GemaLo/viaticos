#!/bin/bash
set -e

# Re-fix permissions at runtime (necessary for bind mounts)
echo "Fixing permissions for storage and bootstrap/cache..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Clear and optimize cache
echo "Clearing and optimizing Laravel cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize

# Process queue jobs in the background
php artisan queue:work --timeout=3600 --tries=1 --memory=1024 --daemon &

# Execute the original command
echo "Starting Apache..."
exec apache2-foreground
