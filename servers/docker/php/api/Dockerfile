FROM composer as buildphp
WORKDIR /var/www
COPY src/php/* ./
RUN composer install --ignore-platform-reqs --no-scripts

FROM node as buildjs
WORKDIR /var/www
COPY src/php/* ./
RUN npm install && npm run build

FROM php:fpm
WORKDIR /var/www
COPY src/php/* ./
COPY --from=buildphp /var/www/vendor/* vendor/
COPY --from=buildjs /var/www/public/js/* public/js/
