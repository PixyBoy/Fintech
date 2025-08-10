# =========================
# Makefile - Advanced Stack
# =========================
# سرویس‌هایت بر اساس docker-compose قبلی:
# mysql, phpmyadmin, php-fpm, apache, redis, horizon
#
# استفاده: make <target>
# مثال:    make artisan cmd="route:list"
# =========================

# ---- Config ----
COMPOSE        := docker compose
APP_SERVICE    := php-fpm
HORIZON_SERVICE:= horizon
DB_SERVICE     := mysql
REDIS_SERVICE  := redis
WEB_SERVICE    := apache

# پوشه کد (جایی که لاراول هست)
APP_PATH       := /var/www/html

# Node در کانتینر یک‌بار مصرف (بدون نیاز به سرویس مجزا)
NODE_IMAGE     := node:20-alpine

# =========================
# Help (خودمستندساز)
# =========================
.PHONY: help
help: ## نمایش لیست دستورات و توضیح کوتاه
	@echo ""
	@echo "Targets:"
	@awk 'BEGIN {FS":.*##"} /^[a-zA-Z0-9_-]+:.*##/ {printf "  \033[36m%-22s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)
	@echo ""

# =========================
# Docker lifecycle
# =========================
.PHONY: up down build restart logs ps prune
up: ## بالا آوردن کل سرویس‌ها در پس‌زمینه
	$(COMPOSE) up -d

down: ## خاموش کردن کل سرویس‌ها
	$(COMPOSE) down

build: ## بیلد بدون کش
	$(COMPOSE) build --no-cache

restart: ## ریستارت کل سرویس‌ها
	$(COMPOSE) down && $(COMPOSE) up -d

logs: ## دیدن لاگ همه سرویس‌ها
	$(COMPOSE) logs -f

ps: ## وضعیت سرویس‌ها
	$(COMPOSE) ps

prune: ## پاکسازی ریسورس‌های بی‌استفاده Docker (با احتیاط)
	docker system prune -f

# =========================
# Laravel / PHP
# =========================
.PHONY: bash artisan migrate seed migrate-refresh tinker cache-clear cache-warm queue-work test pest
bash: ## ورود به شل کانتینر PHP-FPM
	$(COMPOSE) exec $(APP_SERVICE) bash

artisan: ## اجرای artisan: مثال -> make artisan cmd="route:list"
	$(COMPOSE) exec $(APP_SERVICE) php artisan $(cmd)

migrate: ## اجرای مایگریشن‌ها
	$(COMPOSE) exec $(APP_SERVICE) php artisan migrate

seed: ## اجرای سیدرها
	$(COMPOSE) exec $(APP_SERVICE) php artisan db:seed

migrate-refresh: ## ریست دیتابیس + سید
	$(COMPOSE) exec $(APP_SERVICE) php artisan migrate:fresh --seed

tinker: ## اجرای تینکر
	$(COMPOSE) exec $(APP_SERVICE) php artisan tinker

cache-clear: ## پاکسازی همه کش‌های لاراول
	$(COMPOSE) exec $(APP_SERVICE) php artisan optimize:clear

cache-warm: ## ساخت کش‌های کانفیگ و روت
	$(COMPOSE) exec $(APP_SERVICE) sh -lc "php artisan config:cache && php artisan route:cache && php artisan view:cache"

queue-work: ## اجرای صف به صورت ساده (بدون Horizon)
	$(COMPOSE) exec -d $(APP_SERVICE) php artisan queue:work --tries=3 --timeout=90

test: ## اجرای PHPUnit
	$(COMPOSE) exec $(APP_SERVICE) ./vendor/bin/phpunit

pest: ## اجرای Pest (اگر نصب است)
	$(COMPOSE) exec $(APP_SERVICE) ./vendor/bin/pest -p

# =========================
# Composer
# =========================
.PHONY: composer-install composer-update composer-dump
composer-install: ## نصب پکیج‌ها
	$(COMPOSE) exec $(APP_SERVICE) composer install

composer-update: ## آپدیت پکیج‌ها
	$(COMPOSE) exec $(APP_SERVICE) composer update

composer-dump: ## dump-autoload
	$(COMPOSE) exec $(APP_SERVICE) composer dump-autoload -o

# =========================
# Horizon
# =========================
.PHONY: horizon horizon-install horizon-pause horizon-continue horizon-terminate horizon-status
horizon: ## اجرای Horizon
	$(COMPOSE) exec $(HORIZON_SERVICE) php artisan horizon

horizon-install: ## نصب Horizon (ایجاد فایل‌ها)
	$(COMPOSE) exec $(HORIZON_SERVICE) php artisan horizon:install

horizon-pause: ## توقف موقت Horizon
	$(COMPOSE) exec $(HORIZON_SERVICE) php artisan horizon:pause

horizon-continue: ## ادامه کار Horizon
	$(COMPOSE) exec $(HORIZON_SERVICE) php artisan horizon:continue

horizon-terminate: ## خاتمه همه پردازش‌های Horizon
	$(COMPOSE) exec $(HORIZON_SERVICE) php artisan horizon:terminate

horizon-status: ## وضعیت Horizon
	$(COMPOSE) exec $(HORIZON_SERVICE) php artisan horizon:status

# =========================
# Database (MySQL)
# =========================
.PHONY: mysql mysql-dump mysql-restore
mysql: ## ورود به MySQL با کلاینت
	$(COMPOSE) exec $(DB_SERVICE) mysql -u root -proot laravel

# مثال: make mysql-dump file=backup_$(shell date +%F).sql
mysql-dump: ## گرفتن دامپ از دیتابیس laravel -> پارام: file=backup.sql
	@test -n "$(file)" || (echo "Usage: make mysql-dump file=backup.sql"; exit 1)
	$(COMPOSE) exec $(DB_SERVICE) sh -lc 'mysqldump -u root -proot laravel > /tmp/$(file)'
	$(COMPOSE) cp $(DB_SERVICE):/tmp/$(file) ./$(file)
	@echo "Dump saved to ./$(file)"

# مثال: make mysql-restore file=backup.sql
mysql-restore: ## ریستور دامپ به دیتابیس laravel -> پارام: file=backup.sql
	@test -n "$(file)" || (echo "Usage: make mysql-restore file=backup.sql"; exit 1)
	@test -f "$(file)" || (echo "File not found: $(file)"; exit 1)
	$(COMPOSE) cp ./$(file) $(DB_SERVICE):/tmp/restore.sql
	$(COMPOSE) exec $(DB_SERVICE) sh -lc 'mysql -u root -proot laravel < /tmp/restore.sql'
	@echo "Restore finished."

# =========================
# Redis
# =========================
.PHONY: redis-cli redis-flush
redis-cli: ## ورود به Redis CLI
	$(COMPOSE) exec $(REDIS_SERVICE) redis-cli

redis-flush: ## پاک کردن کل دیتا Redis (با احتیاط)
	$(COMPOSE) exec $(REDIS_SERVICE) sh -lc 'redis-cli FLUSHALL'

# =========================
# Frontend (Node داخل کانتینر یک‌بار مصرف)
# =========================
.PHONY: npm-install npm-build npm-dev
npm-install: ## نصب وابستگی‌های فرانت‌اند با Node کانتینری
	docker run --rm -v $$PWD/src:$(APP_PATH) -w $(APP_PATH) $(NODE_IMAGE) sh -lc "npm ci || npm install"

npm-build: ## بیلد پروダکشن
	docker run --rm -v $$PWD/src:$(APP_PATH) -w $(APP_PATH) $(NODE_IMAGE) npm run build

npm-dev: ## اجرای dev (خروجی در ترمینال فعلی)
	docker run --rm -it -p 5173:5173 -v $$PWD/src:$(APP_PATH) -w $(APP_PATH) $(NODE_IMAGE) sh -lc "npm run dev -- --host"

# =========================
# Web / Apache
# =========================
.PHONY: web-logs
web-logs: ## لاگ‌های Apache
	$(COMPOSE) logs -f $(WEB_SERVICE)

# =========================
# Quality / Tools (اختیاری در صورت نصب)
# =========================
.PHONY: pint phpstan rector
pint: ## اجرای Laravel Pint (Code Style)
	$(COMPOSE) exec $(APP_SERVICE) ./vendor/bin/pint

phpstan: ## اجرای PHPStan (Static Analysis)
	$(COMPOSE) exec $(APP_SERVICE) ./vendor/bin/phpstan analyse

rector: ## اجرای Rector (Refactor)
	$(COMPOSE) exec $(APP_SERVICE) ./vendor/bin/rector process
