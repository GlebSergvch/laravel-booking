#!/bin/sh
set -eu

fix_laravel_permissions() {
  if [ -d /var/www/backend/storage ]; then
    mkdir -p /var/www/backend/storage/logs
    touch /var/www/backend/storage/logs/laravel.log || true
    chmod -R 0777 /var/www/backend/storage /var/www/backend/bootstrap/cache || true
  fi
}

fix_laravel_permissions

exec docker-php-entrypoint "$@"
