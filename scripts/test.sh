#!/bin/bash
cd src/php
echo Testing backend
vendor/bin/phpunit --bootstrap ../../test/php/bootstrap.php -c ../../test/php/phpunit.xml
vendor/bin/phpstan analyse Chaplin
vendor/bin/phpcs --standard=PSR2 Chaplin
echo Testing frontend
npm test
cd ../js
echo Testing socket server
npm test
