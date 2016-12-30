#!/bin/bash
echo Setting up Project Chaplin &&
export LANG=en_GB.UTF-8 &&
export LC_ALL=en_GB.UTF-8 &&
locale-gen en_GB.UTF-8 &&
DEBIAN_FRONTEND=noninteractive apt-get update && apt-get -y dist-upgrade &&
DEBIAN_FRONTEND=noninteractive apt-get -y install git curl software-properties-common &&
curl -sL https://deb.nodesource.com/setup_7.x | bash - &&
DEBIAN_FRONTEND=noninteractive add-apt-repository -y ppa:ondrej/php &&
DEBIAN_FRONTEND=noninteractive apt-get update &&
DEBIAN_FRONTEND=noninteractive apt-get -y install git apache2 nodejs build-essential libapache2-mod-php php-xml php-mbstring php-mysql php-cli php-mcrypt php-curl php-amqp php-zip ffmpeg python &&
cp apache/projectchaplin.conf /etc/apache2/sites-available/projectchaplin.conf &&
sed -i "s/\/var\/www/$PWD/g" /etc/apache2/sites-available/projectchaplin.conf &&
npm -g install forever &&
a2enmod rewrite &&
a2enmod headers &&
a2dissite 000-default &&
a2ensite projectchaplin &&
./composer.phar install &&
npm install &&
chown -R www-data:www-data public/uploads &&
chown -R www-data:www-data config/ &&
chown -R www-data:www-data logs/ &&
/etc/init.d/apache2 start &&
echo Done
