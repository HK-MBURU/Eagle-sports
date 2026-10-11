#!/bin/sh
set -e

echo "==> Waiting for MySQL at ${DB_HOST}:${DB_PORT:-3306}…"
until php -r "
  try {
    new PDO(
      'mysql:host=${DB_HOST};port=${DB_PORT:-3306};dbname=${DB_DATABASE}',
      '${DB_USERNAME}',
      '${DB_PASSWORD}'
    );
    exit(0);
  } catch (Exception \$e) { exit(1); }
" 2>/dev/null; do
  printf '.'
  sleep 2
done
echo ""
echo "==> MySQL ready"

echo "==> Clearing stale bootstrap cache…"
rm -f /var/www/html/bootstrap/cache/packages.php \
      /var/www/html/bootstrap/cache/services.php \
      /var/www/html/bootstrap/cache/config.php \
      /var/www/html/bootstrap/cache/routes*.php

echo "==> Running migrations…"
php artisan migrate --force

echo "==> Creating storage symlink…"
php artisan storage:link --quiet || true

echo "==> Caching config, routes and views…"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Fixing storage permissions…"
chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache

echo "==> Starting supervisord…"
exec /usr/bin/supervisord -c /etc/supervisord.conf
