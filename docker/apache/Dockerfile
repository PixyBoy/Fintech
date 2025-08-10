FROM php:8.4-apache

ARG TZ=UTC
ENV TZ=${TZ}

RUN a2enmod rewrite headers proxy proxy_fcgi setenvif

RUN apt-get update && apt-get install -y libzip-dev unzip git \
 && docker-php-ext-install pdo_mysql bcmath zip \
 && pecl install redis \
 && docker-php-ext-enable redis \
 && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
