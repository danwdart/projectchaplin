FROM ubuntu:latest
WORKDIR /var/www
ADD apache/projectchaplin.conf /etc/apache2/sites-available/projectchaplin.conf
EXPOSE 80 1337
ENV LC_ALL=en_GB.UTF-8
ENV LANG=en_GB.UTF-8
ENV REDIS_PORT=6379
RUN locale-gen en_GB.UTF-8
RUN DEBIAN_FRONTEND=noninteractive apt-get update && apt-get -y dist-upgrade
RUN DEBIAN_FRONTEND=noninteractive apt-get -y install git curl software-properties-common
RUN curl -sL https://deb.nodesource.com/setup_6.x | bash -
RUN DEBIAN_FRONTEND=noninteractive add-apt-repository ppa:ondrej/php
RUN DEBIAN_FRONTEND=noninteractive apt-get update
RUN DEBIAN_FRONTEND=noninteractive apt-get -y install git apache2 nodejs build-essential libapache2-mod-php php-xml php-mbstring php-mysql php-cli php-mcrypt php-curl php-amqp php-zip ffmpeg python
RUN npm -g install forever
RUN a2enmod rewrite
RUN a2enmod headers
RUN a2dissite 000-default
RUN a2ensite projectchaplin
RUN cd /var && rm -rf /var/www && git clone https://github.com/kathiedart/projectchaplin.git /var/www
RUN cd /var/www && ./composer.phar install
RUN cd /var/www && npm install
RUN chown -R www-data:www-data /var/www/public/uploads
RUN chown -R www-data:www-data /var/www/config/
RUN chown -R www-data:www-data /var/www/logs/
VOLUME ["/var/www"]
CMD /usr/sbin/apache2ctl -D FOREGROUND
