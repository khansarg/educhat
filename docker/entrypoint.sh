#!/bin/sh
set -e

echo "Starting EduChat..."

# Wait a moment for secrets to be mounted
sleep 1

# Copy the mounted .env file to the app directory
if [ -f /secrets/.env ]; then
    echo "Copying .env from secrets mount..."
    cp /secrets/.env /app/.env
    chown www-data:www-data /app/.env
fi

# Clear any cached config (we're using mounted .env)
php artisan config:clear

# Cache routes and views (these don't depend on env)
echo "Caching routes and views..."
php artisan route:cache
php artisan view:cache

# Run migrations if AUTO_MIGRATE is set
if [ "${AUTO_MIGRATE}" = "true" ] || grep -q "AUTO_MIGRATE=true" /app/.env 2>/dev/null; then
    echo "Running database migrations..."
    php artisan migrate --force
fi

# Create storage symlink if not exists
php artisan storage:link 2>/dev/null || true

echo "Starting FrankenPHP on port ${PORT:-8080}..."

# Start FrankenPHP (Caddy-based server for Laravel)
exec frankenphp run --config /etc/caddy/Caddyfile
