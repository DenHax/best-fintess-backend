FROM composer:2.8

WORKDIR /var/www/html

ENTRYPOINT [ "composer" ]
