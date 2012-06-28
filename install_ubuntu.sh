#!/bin/bash
if [ "0" -ne "$UID" ]
then
	echo "This program must be run as root (as it requires permissions to install packages)."
	exit 1
fi

echo Installing mongo PPA
apt-key adv --keyserver keyserver.ubuntu.com --recv 7F0CEB10
echo "deb http://downloads-distro.mongodb.org/repo/ubuntu-upstart dist 10gen" >> /etc/apt/sources.list
apt-get update

echo Installing dependencies for Ubuntu
apt-get install php-pear php5-dev build-essential mongodb-10gen redis-server libav-tools

echo Installing Mongo PHP extension
pecl install mongo

echo Installing phpredis from git...
git clone git://github.com/nicolasff/phpredis.git
cd phpredis
phpize
./configure
make
make install
cd ..
rm -rf phpredis

echo All done. You can now setup a custom Apache vhost pointing at the public/ directory.
echo For easy setup of this, try installing autopache from git@github.com:dandart/autopache.git
