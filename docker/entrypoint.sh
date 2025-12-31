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

# Clear any cached config and routes (we're using mounted .env)
php artisan config:clear
php artisan route:clear

# Cache views only (route caching not possible with closure routes)
echo "Caching views..."
php artisan view:cache

# Test database connectivity before migrations
echo "Testing database connection..."
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'DB Connected: ' . DB::connection()->getDatabaseName() . PHP_EOL; } catch (\Exception \$e) { echo 'DB Error: ' . \$e->getMessage() . PHP_EOL; }" 2>/dev/null || echo "Warning: Could not test database connection"

# Run migrations (don't fail startup if DB is temporarily unavailable)
echo "Running database migrations..."
php artisan migrate --force || echo "Warning: Migration failed, continuing startup..."

# Create storage symlink if not exists
php artisan storage:link 2>/dev/null || true

echo "Starting FrankenPHP on port ${PORT:-8080}..."

# Start FrankenPHP (Caddy-based server for Laravel)
exec frankenphp run --config /etc/caddy/Caddyfile
