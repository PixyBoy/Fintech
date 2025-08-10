FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libicu-dev \
 && docker-php-ext-install pcntl posix bcmath pdo_mysql intl \
 && pecl install redis \
 && docker-php-ext-enable redis \
 && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html

CMD ["php", "artisan", "horizon"]
