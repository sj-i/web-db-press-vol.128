FROM spiralscout/roadrunner:2.8.8 as rr

FROM php:8.1.4-cli

RUN docker-php-ext-install opcache

RUN apt-get update && apt-get install -y libzip-dev unzip

RUN docker-php-ext-install zip \
  && docker-php-ext-install sockets

COPY --from=composer:2.3.2 /usr/bin/composer /usr/bin/composer

COPY --from=rr /usr/bin/rr /usr/bin/rr
COPY --from=rr /etc/rr.yaml /etc/rr.yaml

COPY . /var/www/html/
RUN chmod a+rwx /var/www/html/var/log

WORKDIR /var/www/html

RUN composer require spiral/roadrunner:2.8.8 nyholm/psr7:1.5.0 symfony/psr-http-message-bridge:2.1.2

USER www-data

ENV APP_ENV=prod
ENV APP_DEBUG=0
ENV APP_SECRET=2ca64f8d83b9e89f5f19d672841d6bb8

ENV DATABASE_URL=sqlite:///%kernel.project_dir%/data/database.sqlite
ENV MAILER_URL=null://localhost

CMD ["/usr/bin/rr", "serve", "-d", "-c", "/etc/rr.yaml"]
