#!/bin/bash
locale-gen en_GB.UTF-8
apt update && apt-get -y dist-upgrade
apt -y install git curl software-properties-common

curl -sL https://deb.nodesource.com/setup_7.x | bash -
add-apt-repository ppa:ondrej/php

apt update
apt -y install git apache2 nodejs build-essential libapache2-mod-php php-xml php-mbstring php-mysql php-cli php-mcrypt php-curl php-amqp php-zip ffmpeg python

curl https://getcomposer.org/composer.phar > /usr/local/bin/composer
chmod +x /usr/local/bin/composer

npm -g install forever

a2enmod rewrite
a2enmod headers
a2dissite 000-default
a2ensite projectchaplin

cd /var
rm -rf /var/www
git clone https://github.com/kathiedart/projectchaplin.git /var/www

cd /var/www
# REMOVE ME
git checkout cs_compile
composer install
npm install
npm run build

chown -R www-data:www-data /var/www/{config,logs,temp,public/uploads}
