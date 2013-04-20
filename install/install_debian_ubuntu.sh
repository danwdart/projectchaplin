#!/bin/bash
if [ "0" -ne "$UID" ]
then
	echo "This program must be run as root (as it requires permissions to install packages)."
	exit 1
fi

echo Installing dependencies for Debian/Ubuntu
apt-get install cmake nodejs php-pear php5-dev build-essential libav-tools php5-mysql

echo Installing the AMQP library
mkdir deps
cd deps
git clone git://github.com/dandart/rabbitmq-c.git
cd rabbitmq-c
mkdir build && cd build
cmake ..
cmake --build .
make install

pecl install amqp

echo extension=amqp.so > /etc/php5/mods-available/amqp.ini
ln -s /etc/php5/mods-available/amqp.ini /etc/php5/conf.d/20-amqp.ini

a2enmod headers
a2enmod rewrite
/etc/init.d/apache2 restart
echo All done. You can now setup a custom Apache vhost pointing at the public/ directory.
echo For easy setup of this, try installing autopache from git@github.com:dandart/autopache.git
