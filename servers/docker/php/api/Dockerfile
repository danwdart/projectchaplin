FROM composer as buildphp
WORKDIR /var/www
COPY src/php /var/www/
RUN composer install --ignore-platform-reqs --no-scripts

FROM node as buildjs
WORKDIR /var/www
COPY src/php /var/www/
RUN npm install && npm run build

FROM php:fpm
RUN docker-php-ext-install pdo pdo_mysql bcmath
WORKDIR /var/www
COPY servers/php-fpm /usr/local/etc/php/conf.d/
COPY src/php /var/www/
RUN chown -R www-data:www-data /var/www/public/uploads
COPY --from=buildphp /var/www/vendor /var/www/vendor
COPY --from=buildjs /var/www/public/js public/js/
