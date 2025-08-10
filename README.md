README – Quick Start (Laravel 12 + Docker)

پیش‌نیازها

Docker Desktop (Compose v2)

Git

پورت‌های آزاد: 80, 8080, 3306, 6379, 5173 (برای Vite dev)

ساختار پروژه (نمای کلی)

<project-root>/
├─ docker-compose.dev.yml            # استک توسعه (php-fpm + Apache + MySQL + Redis + Horizon)
├─ docker-compose.prod.yml           # استک پروداکشن (php-fpm + Nginx + Horizon + Scheduler + Secrets)
├─ Makefile                          # دستورات dev/prod: up/down/build/logs/artisan/...
├─ secrets/                          # فقط prod: Docker secrets (app_key, db_password, db_root_password)
├─ docker/
│  ├─ php/
│  │  ├─ Dockerfile                  # Dev image (php-fpm + ext + composer)
│  │  ├─ Dockerfile.prod             # Prod multi-stage (composer no-dev + Vite build)
│  │  ├─ entrypoint.sh               # Dev: ساخت پوشه‌ها/storage:link/keygen
│  │  ├─ entrypoint.prod.sh          # Prod: cacheها + permissions
│  │  ├─ opcache.ini                 # Dev OPCache (validate_timestamps=1)
│  │  ├─ opcache.prod.ini            # Prod OPCache (validate_timestamps=0 + JIT)
│  │  ├─ www.conf                    # Dev php-fpm
│  │  └─ www.prod.conf               # Prod php-fpm (ping/status)
│  ├─ apache/ (Dev)                  # وب‌سرور Dev
│  └─ nginx/  (Prod)                 # وب‌سرور Prod
└─ src/                              # سورس Laravel 12
   ├─ app/ bootstrap/ config/ public/ resources/ routes/ storage/ vendor/
   ├─ preload.php                    # (Prod) OPcache preload (اختیاری/پیشنهادی)
   ├─ composer.json / composer.lock
   └─ .env                           # تنظیمات لوکال

نکته Dev: vendor و storage داخل کانتینر روی Named Volume هستند؛ پس Composer را همیشه داخل کانتینر اجرا کنید.

اولین اجرا (Dev)

سرویس‌ها را بالا بیاور:

make up
# یا: docker compose -f docker-compose.dev.yml up -d

اگر اولین بار است:

# .env را ست کنید (DB/Redis طبق compose از قبل ست است)
make artisan cmd="key:generate"
make artisan cmd="migrate --force"
make artisan cmd="storage:link"

(اختیاری) فرانت‌اند:

make npm-install
make npm-dev     # Vite dev server روی http://localhost:5173

Horizon:

make up s=horizon     # یا: docker compose -f docker-compose.dev.yml up -d horizon
# داشبورد: http://localhost/horizon

آدرس‌ها

اپ: http://localhost

phpMyAdmin: http://localhost:8080 (user: root / pass: root)

Horizon: http://localhost/horizon

دیپلوی (Prod) – لوکال/سرور

Secrets را بساز:

make MODE=prod prod-secrets-init
# فایل‌های secrets/app_key, db_password, db_root_password ساخته می‌شوند (مقادیر را به‌روز کنید)

Build و بالا آوردن:

make MODE=prod build
make MODE=prod up   # mysql, redis, php-fpm, nginx, horizon, scheduler
make MODE=prod migrate-prod

اپ: http://localhost (Nginx → php-fpm)

Prod: vendor داخل ایمیج bake شده؛ فقط public و storage Volume هستند.

فرمان‌های متداول (Cheat Sheet)

Docker/Stack

make up                 # بالا آوردن stack (dev یا prod با MODE=prod)
make down               # خاموش کردن
make logs               # همه لاگ‌ها
make logs s=php-fpm     # لاگ سرویس خاص
make ps                 # وضعیت سرویس‌ها

Laravel

make artisan cmd="route:list"
make migrate
make cache-clear
make cache-warm
make tinker

Composer (داخل کانتینر – توصیه‌شده)

docker compose -f docker-compose.dev.yml exec -u www-data php-fpm composer require vendor/package:^x.y
docker compose -f docker-compose.dev.yml exec -u www-data php-fpm composer update
docker compose -f docker-compose.dev.yml exec -u www-data php-fpm composer install

Frontend

make npm-install
make npm-dev
make npm-build

Database/Redis

make mysql                 # ورود به MySQL
make mysql-dump file=backup.sql
make mysql-restore file=backup.sql
make redis-cli

Horizon/Scheduler

make horizon-status
make scheduler-logs

قراردادها و نکات تیمی

Composer را فقط داخل کانتینر اجرا کنید (Dev: vendor روی Volume است).

Dev vs Prod parity: Dev (Apache + bind کد) / Prod (Nginx + bake). رفتار اپ یکسان است.

Cacheها: در Dev cacheهای سنگین فعال نیستند؛ در Prod config/route/view/event:cache فعال‌اند.

Log کندی: php-fpm slowlog روشن است؛ برای پروفایل می‌توانید Clockwork را فقط روی local اضافه کنید.

مهاجرت‌ها: هر PR با migration جدید → بعد از merge در Prod artisan-migrate اجرا می‌شود.

Secrets: هرگز .env Prod را کامیت نکنید. از secrets/ استفاده می‌کنیم.

رفع خطاهای رایج

Permission denied روی storage/frameworkentrypoint به‌صورت خودکار درست می‌کند؛ اگر لازم شد:

docker compose -f docker-compose.dev.yml exec php-fpm bash -lc "chown -R www-data:www-data storage bootstrap/cache"

پکیج نصب شده روی هاست، ولی داخل کانتینر دیده نمی‌شودDev از Volume برای vendor استفاده می‌کند → داخل کانتینر composer install بزنید:

docker compose -f docker-compose.dev.yml exec -u www-data php-fpm composer install

پورت 80 یا 3306 اشغال استسرویس‌های متداخل را خاموش کنید یا پورت‌ها را در compose تغییر دهید.

APP_KEY missing / 500

make artisan cmd="key:generate"

Performance کوتاه

Dev: OPcache فعال با validate_timestamps=1 + file_cache برای artisan.

Prod: OPcache با validate_timestamps=0 + JIT + preload (src/preload.php).

php-fpm تیون (Prod): pm.max_children را متناسب با منابع تنظیم کنید.

DB/Redis: تنظیمات پیشنهادی در docker/mysql/my.cnf و Redis AOF (اختیاری).