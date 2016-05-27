FROM ubuntu:latest
VOLUME ["/var/www"]
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
RUN DEBIAN_FRONTEND=noninteractive apt-get -y install apache2 nodejs build-essential libapache2-mod-php php-xml php-mbstring php-mysql php-cli php-mcrypt php-curl
RUN npm -g install forever
RUN a2enmod rewrite
RUN a2dissite 000-default
RUN a2ensite projectchaplin
CMD ./composer.phar install && npm install && ./cli.sh start && /usr/sbin/apache2ctl -D FOREGROUND
