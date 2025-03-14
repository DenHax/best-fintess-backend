FROM composer:1.8

WORKDIR /var/www/html

ENTRYPOINT [ "composer" ]
