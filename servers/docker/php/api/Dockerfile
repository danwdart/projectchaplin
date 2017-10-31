FROM composer as buildphp
WORKDIR /var/www
COPY src/php /var/www/
RUN composer install --ignore-platform-reqs --no-scripts

FROM node as buildjs
WORKDIR /var/www
COPY src/php /var/www/
RUN npm install && npm run build

FROM php:fpm
WORKDIR /var/www
COPY src/php /var/www/
COPY --from=buildphp /var/www/vendor /var/www/vendor
COPY --from=buildjs /var/www/public/js public/js/
