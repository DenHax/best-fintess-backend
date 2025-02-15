FROM php:8.2-apache

ARG PUID=1000
ARG PGID=1000
RUN groupmod -o -g ${PGID} www-data && \
  usermod -o -u ${PUID} -g www-data www-data

RUN chown -R www-data:www-data /var/www/html

RUN apt-get update -y && apt-get install -y libpq-dev gzip && docker-php-ext-install pdo pdo_mysql pdo_pgsql

COPY ./docker/apache.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite proxy proxy_http

COPY ./src /var/www/html/
