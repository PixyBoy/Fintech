```markdown
# Laravel + Docker Dev Stack

این پروژه یک محیط توسعه کامل برای لاراول را با استفاده از Docker فراهم می‌کند، شامل:

- **MySQL 8**
- **phpMyAdmin**
- **PHP-FPM 8.3** (با اکستنشن‌های لازم برای لاراول)
- **Apache 2**
- **Redis 7**
- **Laravel Horizon** (در محیط لینوکسی داخل داکر)

---

## 📂 ساختار پوشه‌ها

```
project-root/
├── docker/
│   ├── php-fpm/
│   │   └── Dockerfile
│   ├── apache/
│   │   └── Dockerfile
│   └── horizon/
│       └── Dockerfile
├── docker-compose.yml
├── Makefile
└── src/               # کد لاراول
```

---

## ⚙️ پیش‌نیازها

- **Docker** و **Docker Compose**
- **Make** (در ویندوز می‌توانی از Git Bash استفاده کنی)

---

## 🛠 توضیح فایل‌ها

### `docker/php-fpm/Dockerfile`
- بیس: `php:8.3-fpm`
- اکستنشن‌ها: `pdo_mysql`, `bcmath`, `zip`, `intl`, `gd`, `redis`
- Composer داخل ایمیج
- مسیر کاری: `/var/www/html`

### `docker/apache/Dockerfile`
- بیس: `php:8.3-apache`
- اکستنشن‌ها: `pdo_mysql`, `bcmath`, `zip`, `redis`
- فعال‌سازی `mod_rewrite` برای لاراول
- مسیر کاری: `/var/www/html`

### `docker/horizon/Dockerfile`
- بیس: `php:8.3-cli`
- اکستنشن‌ها: `pcntl`, `posix`, `bcmath`, `pdo_mysql`, `intl`, `redis`
- Composer داخل ایمیج
- مسیر کاری: `/var/www/html`
- دستور پیش‌فرض: اجرای Horizon

---

## 🚀 مراحل راه‌اندازی

1. **بالا آوردن سرویس‌ها**
```bash
make up
```

2. **نصب وابستگی‌های Composer**
```bash
make composer-install
```

3. **اجرای مایگریشن‌ها**
```bash
make migrate
```

4. **نصب Horizon (یک‌بار)**
```bash
make horizon-install
```

5. **اجرای Horizon**
```bash
make horizon
```

---

## 🌐 دسترسی‌ها

| سرویس       | آدرس                  | اطلاعات ورود |
|-------------|----------------------|--------------|
| Laravel App | http://localhost     | — |
| phpMyAdmin  | http://localhost:8080 | یوزر: root / پسورد: root |
| Redis       | localhost:6379       | — |

---

## 📌 تنظیمات `.env` (داخل `src/.env`)

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=laravel

REDIS_HOST=redis
REDIS_PORT=6379

APP_URL=http://localhost
```

---

## 📋 دستورات پرکاربرد Makefile

### 🔹 مدیریت Docker
| دستور     | توضیح |
|-----------|-------|
| `make up` | بالا آوردن سرویس‌ها |
| `make down` | خاموش کردن سرویس‌ها |
| `make restart` | ریستارت سرویس‌ها |
| `make logs` | دیدن لاگ همه سرویس‌ها |
| `make ps` | وضعیت سرویس‌ها |
| `make prune` | پاکسازی منابع بدون استفاده |

### 🔹 Laravel
| دستور | توضیح |
|-------|-------|
| `make artisan cmd="route:list"` | اجرای دستور artisan |
| `make migrate` | اجرای مایگریشن‌ها |
| `make seed` | اجرای سیدرها |
| `make migrate-refresh` | ریست دیتابیس + سید |
| `make cache-clear` | پاک کردن کش |
| `make cache-warm` | ساخت کش‌های کانفیگ و روت |
| `make tinker` | اجرای تینکر |
| `make queue-work` | اجرای صف بدون Horizon |
| `make test` | اجرای PHPUnit |
| `make pest` | اجرای Pest |

### 🔹 Composer
| دستور | توضیح |
|-------|-------|
| `make composer-install` | نصب پکیج‌ها |
| `make composer-update` | آپدیت پکیج‌ها |
| `make composer-dump` | dump-autoload |

### 🔹 Horizon
| دستور | توضیح |
|-------|-------|
| `make horizon` | اجرای Horizon |
| `make horizon-install` | نصب Horizon |
| `make horizon-pause` | توقف موقت Horizon |
| `make horizon-continue` | ادامه کار Horizon |
| `make horizon-terminate` | خاتمه Horizon |
| `make horizon-status` | وضعیت Horizon |

### 🔹 دیتابیس
| دستور | توضیح |
|-------|-------|
| `make mysql` | ورود به MySQL |
| `make mysql-dump file=backup.sql` | گرفتن بکاپ |
| `make mysql-restore file=backup.sql` | ریستور بکاپ |

### 🔹 Redis
| دستور | توضیح |
|-------|-------|
| `make redis-cli` | ورود به Redis CLI |
| `make redis-flush` | پاک کردن کل دیتا Redis |

### 🔹 فرانت‌اند (Node داخل کانتینر)
| دستور | توضیح |
|-------|-------|
| `make npm-install` | نصب وابستگی‌ها |
| `make npm-build` | بیلد پروڈاکشن |
| `make npm-dev` | اجرای dev |

---

## 🗄 بکاپ و ریستور دیتابیس

**بکاپ:**
```bash
make mysql-dump file=backup.sql
```

**ریستور:**
```bash
make mysql-restore file=backup.sql
```

---

## 📜 نکات

- Horizon روی ویندوز اجرا نمی‌شود؛ این ساختار آن را داخل کانتینر لینوکسی اجرا می‌کند.
- همه سرویس‌ها از یک کدبیس (`./src`) استفاده می‌کنند.
- فرانت‌اند بدون سرویس Node و با کانتینر موقتی اجرا می‌شود.
- برای دیدن تمام دستورات، بزن:
```bash
make help
```
```