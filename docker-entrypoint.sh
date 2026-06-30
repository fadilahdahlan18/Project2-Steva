#!/bin/bash
set -e

# -------------------------------------------------------
# Railway dynamic PORT support
# -------------------------------------------------------
APACHE_PORT=${PORT:-80}

echo "[entrypoint] Starting with PORT=${APACHE_PORT}"

# Update Apache to listen on the correct port
sed -i "s/Listen 80/Listen ${APACHE_PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${APACHE_PORT}>/" /etc/apache2/sites-available/000-default.conf

# -------------------------------------------------------
# Generate APP_KEY if not set
# -------------------------------------------------------
if [ -z "$APP_KEY" ]; then
    echo "[entrypoint] Generating APP_KEY..."
    php artisan key:generate --force
fi

# -------------------------------------------------------
# Laravel setup (non-fatal so Apache still starts)
# -------------------------------------------------------
echo "[entrypoint] Running migrations..."
php artisan migrate --force || echo "[entrypoint] WARNING: migrate failed, continuing..."

echo "[entrypoint] Caching config/routes/views..."
php artisan config:cache  || true
php artisan route:cache   || true
php artisan view:cache    || true

# -------------------------------------------------------
# Start Apache
# -------------------------------------------------------
echo "[entrypoint] Starting Apache on port ${APACHE_PORT}..."
exec apache2-foreground
