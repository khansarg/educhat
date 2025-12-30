# =============================================================================
# Stage 1: Build Frontend Assets (Vite)
# =============================================================================
FROM node:20-alpine AS frontend-builder

WORKDIR /app

# Copy package files
COPY package.json package-lock.json ./

# Install dependencies
RUN npm ci

# Copy frontend source files
COPY resources/ resources/
COPY vite.config.js tailwind.config.js postcss.config.js ./

# Build assets
RUN npm run build

# =============================================================================
# Stage 2: Composer Dependencies
# =============================================================================
FROM composer:2 AS composer-builder

WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies (no dev, no scripts yet)
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --ignore-platform-reqs

# =============================================================================
# Stage 3: Production Image with FrankenPHP
# =============================================================================
FROM dunglas/frankenphp:latest-php8.2-alpine AS production

# Install additional PHP extensions
RUN install-php-extensions \
    pdo_mysql \
    redis \
    gd \
    zip \
    intl \
    opcache \
    pcntl \
    bcmath

# Set working directory
WORKDIR /app

# Copy composer dependencies from builder
COPY --from=composer-builder /app/vendor vendor/

# Copy application code
COPY . .

# Copy built frontend assets from Stage 1
COPY --from=frontend-builder /app/public/build public/build/

# Install composer & generate optimized autoloader
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer dump-autoload --optimize --no-dev

# Create required directories
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chown -R www-data:www-data /app \
    && chmod -R 755 storage bootstrap/cache

# PHP OPcache configuration for production
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=16" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=20000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.jit=1255" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.jit_buffer_size=128M" >> /usr/local/etc/php/conf.d/opcache.ini

# Copy Caddyfile configuration
COPY docker/Caddyfile /etc/caddy/Caddyfile

# Copy entrypoint script
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Cloud Run port
ENV PORT=8080
ENV SERVER_NAME=:${PORT}

EXPOSE 8080

# Health check
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD curl -f http://localhost:${PORT}/up || exit 1

ENTRYPOINT ["/entrypoint.sh"]
