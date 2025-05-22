FROM php:8.4-fpm

RUN apt-get update && apt-get install -y git && apt-get install -y zip

RUN docker-php-ext-install pdo_mysql

RUN pecl install xdebug && docker-php-ext-enable xdebug

COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

