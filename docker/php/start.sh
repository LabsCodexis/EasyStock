#!/usr/bin/env sh
set -e

php artisan migrate --force

if [ "$#" -gt 0 ]; then
    exec "$@"
fi

exec php -S 0.0.0.0:${PORT:-8080} -t public
