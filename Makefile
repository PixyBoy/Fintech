# =========================
# Makefile - Dev & Prod Stack (Laravel)
# =========================
# پشتیبانی هم‌زمان از دو پروفایل:
#   - MODE=dev  (پیش‌فرض)  -> docker-compose.dev.yml
#   - MODE=prod             -> docker-compose.prod.yml
#
# Examples:
#   make up                         # dev up
#   make MODE=prod up               # prod up
#   make artisan cmd="route:list"   # dev artisan
#   make MODE=prod artisan cmd="horizon:status"  # prod artisan
#   make logs                       # all services logs (follow)
#   make logs s=php-fpm             # only php-fpm logs
#   make deploy-prod                # build+run prod flow
# =========================

# ---- Mode selection ----
MODE ?= dev

ifeq ($(MODE),dev)
	COMPOSE        := docker compose -f docker-compose.dev.yml
	APP_SERVICE    := php-fpm
	WEB_SERVICE    := apache
	DB_SERVICE     := mysql
	REDIS_SERVICE  := redis
	HORIZON_SERVICE:= horizon
	SCHEDULER_SERVICE := $(APP_SERVICE) # در dev scheduler جدا نداریم، artisan دستی می‌زنیم
	APP_PATH       := /var/www/html
	NODE_IMAGE     := node:20-alpine
	# MySQL creds (dev)
	DB_ROOT_AUTH   := -u root -proot
	DB_NAME        := laravel
else ifeq ($(MODE),prod)
	COMPOSE        := docker compose -f docker-compose.prod.yml
	APP_SERVICE    := php-fpm
	WEB_SERVICE    := nginx
	DB_SERVICE     := mysql
	REDIS_SERVICE  := redis
	HORIZON_SERVICE:= horizon
	SCHEDULER_SERVICE := scheduler
	APP_PATH       := /var/www/html
	NODE_IMAGE     := node:20-alpine
	# MySQL creds (prod) از secrets خوانده می‌شود
	DB_ROOT_AUTH   := -u root -p"$$(cat /run/secrets/db_root_password)"
	DB_NAME        := laravel
else
	$(error MODE باید dev یا prod باشد: `make MODE=prod up`)
endif

# =========================
# Help (self-documented)
# =========================
.PHONY: help
help: ## نمایش لیست دستورات و توضیح کوتاه
	@echo ""
	@echo "Mode: $(MODE)"
	@echo "Compose: $(COMPOSE)"
	@echo ""
	@echo "Targets:"
	@awk 'BEGIN {FS":.*##"} /^[a-zA-Z0-9_.-]+:.*##/ {printf "  \033[36m%-24s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)
	@echo ""
	@echo "Examples:"
	@echo "  make up                        # dev up"
	@echo "  make MODE=prod up              # prod up"
	@echo "  make artisan cmd=\"route:list\"  # dev artisan"
	@echo "  make MODE=prod logs s=nginx    # prod logs for nginx"
	@echo ""

# =========================
# Docker lifecycle
# =========================
.PHONY: up down build restart logs ps prune pull
up: ## بالا آوردن سرویس‌های حالت جاری (MODE)
	$(COMPOSE) up -d

down: ## خاموش کردن سرویس‌های حالت جاری (MODE)
	$(COMPOSE) down

build: ## بیلد (از کش استفاده می‌کند)
	$(COMPOSE) build

rebuild: ## بیلد بدون کش
	$(COMPOSE) build --no-cache

restart: ## ریستارت سرویس‌ها
	$(COMPOSE) down && $(COMPOSE) up -d

logs: ## مشاهده لاگ‌ها (پارام s=service برای محدودکردن)
	$(COMPOSE) logs -f $(s)

ps: ## وضعیت سرویس‌ها
	$(COMPOSE) ps

pull: ## pull ایمیج‌ها
	$(COMPOSE) pull

prune: ## پاکسازی ریسورس‌های بی‌استفاده Docker (با احتیاط)
	docker system prune -f

# =========================
# Laravel / PHP
# =========================
.PHONY: bash artisan migrate seed migrate-refresh tinker cache-clear cache-warm queue-work test pest
bash: ## ورود به شل کانتینر PHP (MODE جاری)
	$(COMPOSE) exec $(APP_SERVICE) bash

artisan: ## اجرای artisan -> make artisan cmd="route:list"
	@test -n "$(cmd)" || (echo "Usage: make artisan cmd=\"...\""; exit 1)
	$(COMPOSE) exec $(APP_SERVICE) php artisan $(cmd)

migrate: ## اجرای مایگریشن‌ها
	$(COMPOSE) exec $(APP_SERVICE) php artisan migrate --force

seed: ## اجرای سیدرها
	$(COMPOSE) exec $(APP_SERVICE) php artisan db:seed --force

migrate-refresh: ## ریست دیتابیس + سید
	$(COMPOSE) exec $(APP_SERVICE) php artisan migrate:fresh --seed --force

tinker: ## اجرای تینکر
	$(COMPOSE) exec $(APP_SERVICE) php artisan tinker

cache-clear: ## پاکسازی همه کش‌های لاراول
	$(COMPOSE) exec $(APP_SERVICE) php artisan optimize:clear

cache-warm: ## ساخت کش‌های کانفیگ و روت و ویو
	$(COMPOSE) exec $(APP_SERVICE) sh -lc "php artisan config:cache && php artisan route:cache && php artisan view:cache"

queue-work: ## اجرای صف ساده (بدون Horizon)
	$(COMPOSE) exec -d $(APP_SERVICE) php artisan queue:work --tries=3 --timeout=90

test: ## اجرای PHPUnit
        $(COMPOSE) exec $(APP_SERVICE) ./vendor/bin/phpunit

lint: ## اجرای Pint (کدنویسی منظم)
        $(COMPOSE) exec $(APP_SERVICE) ./vendor/bin/pint

pest: ## اجرای Pest (اگر نصب است)
        $(COMPOSE) exec $(APP_SERVICE) ./vendor/bin/pest -p

# =========================
# Composer
# =========================
.PHONY: composer-install composer-update composer-dump
composer-install: ## نصب پکیج‌ها (dev: bind mount | prod: داخل ایمیج وجود دارد)
	$(COMPOSE) exec $(APP_SERVICE) composer install

composer-update: ## آپدیت پکیج‌ها
	$(COMPOSE) exec $(APP_SERVICE) composer update

composer-dump: ## dump-autoload
	$(COMPOSE) exec $(APP_SERVICE) composer dump-autoload -o

# =========================
# Horizon
# =========================
.PHONY: horizon horizon-install horizon-pause horizon-continue horizon-terminate horizon-status horizon-logs
horizon: ## اجرای Horizon در سرویس horizon (اگر در حال اجراست: noop)
	$(COMPOSE) exec $(HORIZON_SERVICE) php artisan horizon

horizon-install: ## horizon:install
	$(COMPOSE) exec $(HORIZON_SERVICE) php artisan horizon:install

horizon-pause: ## توقف موقت Horizon
	$(COMPOSE) exec $(HORIZON_SERVICE) php artisan horizon:pause

horizon-continue: ## ادامه کار Horizon
	$(COMPOSE) exec $(HORIZON_SERVICE) php artisan horizon:continue

horizon-terminate: ## خاتمه همه پردازش‌های Horizon
	$(COMPOSE) exec $(HORIZON_SERVICE) php artisan horizon:terminate

horizon-status: ## وضعیت Horizon
	$(COMPOSE) exec $(HORIZON_SERVICE) php artisan horizon:status

horizon-logs: ## لاگ‌های horizon
	$(COMPOSE) logs -f $(HORIZON_SERVICE)

# =========================
# Scheduler (Prod has dedicated service)
# =========================
.PHONY: scheduler-start scheduler-logs
scheduler-start: ## در dev: اجرای schedule:work در php-fpm (پس‌زمینه). در prod: سرویس scheduler از قبل up می‌شود.
ifeq ($(MODE),dev)
	$(COMPOSE) exec -d $(APP_SERVICE) php artisan schedule:work
else
	@echo "In prod, scheduler runs as a dedicated service ($(SCHEDULER_SERVICE)). Use: make logs s=$(SCHEDULER_SERVICE)"
endif

scheduler-logs: ## مشاهده لاگ‌های scheduler (prod)
	$(COMPOSE) logs -f $(SCHEDULER_SERVICE)

# =========================
# Database (MySQL)
# =========================
.PHONY: mysql mysql-dump mysql-restore
mysql: ## ورود به MySQL (client)
	$(COMPOSE) exec $(DB_SERVICE) sh -lc 'mysql $(DB_ROOT_AUTH) $(DB_NAME)'

# مثال: make mysql-dump file=backup_$(shell date +%F).sql
mysql-dump: ## گرفتن دامپ از دیتابیس laravel -> پارام: file=backup.sql
	@test -n "$(file)" || (echo "Usage: make mysql-dump file=backup.sql"; exit 1)
	$(COMPOSE) exec $(DB_SERVICE) sh -lc 'mysqldump $(DB_ROOT_AUTH) $(DB_NAME) > /tmp/$(file)'
	$(COMPOSE) cp $(DB_SERVICE):/tmp/$(file) ./$(file)
	@echo "Dump saved to ./$(file)"

# مثال: make mysql-restore file=backup.sql
mysql-restore: ## ریستور دامپ به دیتابیس laravel -> پارام: file=backup.sql
	@test -n "$(file)" || (echo "Usage: make mysql-restore file=backup.sql"; exit 1)
	@test -f "$(file)" || (echo "File not found: $(file)"; exit 1)
	$(COMPOSE) cp ./$(file) $(DB_SERVICE):/tmp/restore.sql
	$(COMPOSE) exec $(DB_SERVICE) sh -lc 'mysql $(DB_ROOT_AUTH) $(DB_NAME) < /tmp/restore.sql'
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
# Frontend (Node one-off) - Dev only
# =========================
.PHONY: npm-install npm-build npm-dev
npm-install: ## نصب وابستگی‌های فرانت‌اند (dev)
ifeq ($(MODE),dev)
	docker run --rm -v $$PWD/src:$(APP_PATH) -w $(APP_PATH) $(NODE_IMAGE) sh -lc "npm ci || npm install"
else
	@echo "Skip: In prod, assets are built inside Dockerfile.prod"
endif

npm-build: ## بیلد پروڈاکشن (dev) -> خروجی public/build
ifeq ($(MODE),dev)
	docker run --rm -v $$PWD/src:$(APP_PATH) -w $(APP_PATH) $(NODE_IMAGE) npm run build
else
	@echo "Skip: In prod, assets are built inside Dockerfile.prod"
endif

npm-dev: ## اجرای vite dev server (پرت 5173)
ifeq ($(MODE),dev)
	docker run --rm -it -p 5173:5173 -v $$PWD/src:$(APP_PATH) -w $(APP_PATH) $(NODE_IMAGE) sh -lc "npm run dev -- --host"
else
	@echo "Skip: In prod, vite dev server is not used."
endif

# =========================
# Web server logs
# =========================
.PHONY: web-logs
web-logs: ## مشاهده لاگ‌های وب‌سرور (apache در dev / nginx در prod)
	$(COMPOSE) logs -f $(WEB_SERVICE)

# =========================
# Prod-only helpers
# =========================
.PHONY: prod-secrets-init deploy-prod migrate-prod up-core-prod
prod-secrets-init: ## ایجاد secrets لازم برای prod (اگر وجود ندارند)
	@mkdir -p secrets
	@if [ ! -f secrets/app_key ]; then echo ">> secrets/app_key ایجاد شد (مقدار APP_KEY لاراول را اینجا بگذارید: base64:....)"; echo "base64:PUT-YOUR-APP-KEY-HERE" > secrets/app_key; fi
	@if [ ! -f secrets/db_password ]; then echo "laravel" > secrets/db_password; echo ">> secrets/db_password=laravel"; fi
	@if [ ! -f secrets/db_root_password ]; then echo "root" > secrets/db_root_password; echo ">> secrets/db_root_password=root"; fi

up-core-prod: ## بالا آوردن coreهای prod (mysql, redis) - برای سناریوی استقرار مرحله‌ای
	$(MAKE) MODE=prod up s=mysql
	$(MAKE) MODE=prod up s=redis
	# اگر دستور بالا سرویسی مشخص نکرد، کل stack بالا میاد. برای کنترل دستی:
	# docker compose -f docker-compose.prod.yml up -d mysql redis

migrate-prod: ## اجرای مایگریشن prod (one-shot service)
	$(COMPOSE) run --rm artisan-migrate

deploy-prod: prod-secrets-init ## Build & Run کامل prod
	$(COMPOSE) build
	$(COMPOSE) up -d mysql redis
	$(COMPOSE) run --rm artisan-migrate
	$(COMPOSE) up -d php-fpm nginx horizon scheduler
	@echo ">> Prod deployed. Open http://localhost"

-include git.Makefile