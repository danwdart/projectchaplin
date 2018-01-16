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
# ffmpeg requires jessie backports
# youtube-dl requires python
# In the future this should aim to be a separate service with an API
RUN echo "deb http://ftp.debian.org/debian jessie-backports main" >> /etc/apt/sources.list.d/backports.list && \
    apt-get update && \
    apt-get -y install ffmpeg python && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*
RUN curl -L https://yt-dl.org/downloads/latest/youtube-dl -o /usr/local/bin/youtube-dl
RUN chmod a+rx /usr/local/bin/youtube-dl
WORKDIR /var/www
COPY servers/php-fpm /usr/local/etc/php/conf.d/
COPY src/php /var/www/
RUN chown -R www-data:www-data /var/www/public/uploads
COPY --from=buildphp /var/www/vendor /var/www/vendor
COPY --from=buildjs /var/www/public/js /var/www/public/js
