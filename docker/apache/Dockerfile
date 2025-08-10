FROM php:8.3-apache

RUN apt-get update && apt-get install -y libzip-dev unzip git \
 && docker-php-ext-install pdo_mysql bcmath zip \
 && pecl install redis \
 && docker-php-ext-enable redis

RUN a2enmod rewrite

WORKDIR /var/www/html
