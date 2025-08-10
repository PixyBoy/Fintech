# docker/php/Dockerfile
FROM php:8.3-fpm

ARG PUID=1000
ARG PGID=1000
ARG TZ=UTC

ENV COMPOSER_HOME=/tmp/composer
ENV TZ=${TZ}

# سیستم‌عامل و اکستنشن‌ها
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libicu-dev libpng-dev libonig-dev libpq-dev \
    libfreetype6-dev libjpeg62-turbo-dev zlib1g-dev libxml2-dev libssl-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j$(nproc) pdo_mysql bcmath zip intl gd opcache pcntl \
 && pecl install redis \
 && docker-php-ext-enable redis \
 && rm -rf /var/lib/apt/lists/*

# OPcache و PHP-FPM config
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/php/www.conf     /usr/local/etc/php-fpm.d/www.conf

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Entrypoint
COPY docker/php/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

WORKDIR /var/www/html

# همسان‌سازی UID/GID برای www-data (برای مالکیت درست فایل‌ها)
RUN groupmod -o -g ${PGID} www-data && \
    usermod  -o -u ${PUID} -g www-data www-data

# مهم: کاربر را تغییر نمی‌دهیم (root می‌ماند) تا بتوانیم mkdir/chown روی ولوم‌ها انجام دهیم
ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm"]
