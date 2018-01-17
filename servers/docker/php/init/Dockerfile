FROM composer as build
WORKDIR /var/www
COPY src/php /var/www/
RUN composer install --ignore-platform-reqs --no-scripts

FROM php
RUN docker-php-ext-install pdo pdo_mysql bcmath
WORKDIR /var/www
COPY src/php /var/www/

USER www-data

CMD ["php", "cli/cli.php", "init", "adminuser"]
