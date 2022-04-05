FROM php:8.1.4-fpm

RUN docker-php-ext-install opcache

COPY --from=composer:2.3.2 /usr/bin/composer /usr/bin/composer

COPY . /var/www/html/
RUN chmod a+rwx /var/www/html/var/log

WORKDIR /var/www/html
