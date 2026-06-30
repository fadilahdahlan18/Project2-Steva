#!/bin/bash
set -e

APP_PORT=${PORT:-8080}

echo "[start] Booting Project2-Steva on port $APP_PORT"

# Generate APP_KEY jika belum di-set
if [ -z "$APP_KEY" ]; then
    echo "[start] Generating APP_KEY..."
    php artisan key:generate --force || true
fi

# Cache config/routes/views (non-fatal)
echo "[start] Caching Laravel config..."
php artisan config:cache || true
php artisan route:cache  || true
php artisan view:cache   || true

# Jalankan migration di background setelah server siap
(
    sleep 15
    echo "[migrate] Running migrations..."
    php artisan migrate --force && echo "[migrate] Done!" || echo "[migrate] WARNING: failed"
) &

# Start PHP built-in server (no Apache, no MPM issues)
echo "[start] Starting server on 0.0.0.0:$APP_PORT ..."
exec php artisan serve --host=0.0.0.0 --port=$APP_PORT
