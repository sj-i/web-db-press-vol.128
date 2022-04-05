FROM php:8.1.4-apache

RUN docker-php-ext-install opcache

COPY --from=composer:2.3.2 /usr/bin/composer /usr/bin/composer

COPY . /var/www/html/

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN chmod a+rwx /var/www/html/var/log

RUN a2enmod rewrite

WORKDIR /var/www/html
