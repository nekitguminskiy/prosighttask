#!/bin/bash

# Wait for database to be ready
echo "Waiting for database to be ready..."
wait-for-it db:5432 --timeout=30

# Check Composer version
echo "Checking Composer version..."
composer --version

# Install/Update Composer dependencies (including dev for PHPStan)
echo "Installing Composer dependencies..."
composer install --with-dev-dependencies
composer require --dev "larastan/larastan"

# Run PHPStan analysis
echo "Running PHPStan analysis..."
if [ -f "./vendor/bin/phpstan" ]; then
    php ./vendor/bin/phpstan analyse --memory-limit=2G
else
    echo "PHPStan not found, skipping analysis..."
fi
# Generate application key if not exists
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi


# Cache configuration for production
echo "Caching Laravel configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Run database seeders
echo "Running database seeders..."
php artisan db:seed --force

# Create storage symlink
echo "Creating storage symlink..."
php artisan storage:link

# Set proper permissions
echo "Setting permissions..."
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Start Apache
echo "Starting Apache..."
exec apache2-foreground
