#!/usr/bin/env bash
set -e

cd /var/www/html
umask 0002  # اجازه‌ی write گروه

# اگر پروژه هنوز mount نشده
if [ ! -f composer.json ]; then
  echo ">> composer.json not found in /var/www/html - mount your Laravel app to ./src"
  exec "$@"
fi

# ساخت پوشه‌های ضروری قبل از هر artisan (fix: View path not found)
mkdir -p storage/framework/{cache,data,sessions,testing,views} bootstrap/cache

# نصب vendor اگر وجود ندارد
if [ ! -f vendor/autoload.php ]; then
  echo ">> composer install (no vendor detected)"
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# ساخت APP_KEY اگر نبود
if ! grep -qE '^APP_KEY=base64:' .env 2>/dev/null; then
  if [ -f .env ]; then
    echo ">> php artisan key:generate"
    php artisan key:generate --force
  fi
fi

# لینک storage
if [ ! -L public/storage ]; then
  echo ">> php artisan storage:link"
  php artisan storage:link || true
fi

# Dev: کش‌های سنگین نزن
if [ "${APP_ENV}" != "local" ] && [ "${APP_ENV}" != "development" ]; then
  echo ">> php artisan config:cache && route:cache && view:cache"
  php artisan config:cache || true
  php artisan route:cache  || true
  php artisan view:cache   || true
fi

# اطمینان از مالکیت/مجوز
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R ug+rw storage bootstrap/cache || true

echo ">> ready; starting main process"
exec "$@"
