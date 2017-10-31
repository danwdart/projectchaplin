FROM composer as build
WORKDIR /var/www
COPY src/php /var/www/
RUN composer install --ignore-platform-reqs --no-scripts

FROM php
WORKDIR /var/www
COPY src/php /var/www/
COPY --from=build /var/www/vendor /var/www/vendor

CMD ["php", "cli/cli.php", "cli", "youtube"]
