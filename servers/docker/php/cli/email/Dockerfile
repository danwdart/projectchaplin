FROM composer as build
WORKDIR /var/www
COPY src/php/* ./
RUN composer install --ignore-platform-reqs --no-scripts

FROM php
WORKDIR /var/www
COPY src/php/* ./
COPY --from=build /var/www/vendor/* vendor/

CMD ["php", "cli/cli.php", "cli", "email"]
