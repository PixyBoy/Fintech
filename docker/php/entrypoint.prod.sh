#!/usr/bin/env bash
set -e

cd /var/www/html
umask 0002

# helper: خواندن متغیر از فایل secret
export_from_file() {
  var_name="$1"
  file_var="${var_name}_FILE"
  eval file_path="\$$file_var"
  eval current_val="\$$var_name"
  if [ -z "$current_val" ] && [ -f "$file_path" ]; then
    export "$var_name"="$(cat "$file_path" | tr -d '\r\n')"
  fi
}

# خواندن APP_KEY و DB_PASSWORD از secrets (اگر با *_FILE آمده باشند)
export_from_file APP_KEY
export_from_file DB_PASSWORD

# ساخت پوشه‌های ضروری روی ولوم‌ها
mkdir -p storage/framework/{cache,data,sessions,testing,views} bootstrap/cache

# لینک storage اگر نبود
if [ ! -L public/storage ]; then
  php artisan storage:link || true
fi

# بهینه‌سازی‌های تولید
php artisan event:cache    || true
php artisan config:cache   || true
php artisan route:cache    || true
php artisan view:cache     || true

# اطمینان از مالکیت/مجوز
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R ug+rw storage bootstrap/cache || true

echo ">> app ready (prod)"
exec "$@"
