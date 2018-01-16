# Project Chaplin

An open source, self-hosted video sharing service

## Current features
* AGPLv3 License
* No intrusive adverts
* No restrictions on content by country, IP or government
* Video downloading as standard
* HTML5 WebM as standard
* Brightness/Contrast controls
* Easter eggs?
* [12-Factor App](https://www.12factor.net/)
* Fully Docker service compatible

## Future features
* Live streaming (pluginless) - to be re-implemented
* No staff blocking, auto-blocking only on public demand
* REST APIs
* Public tagging
* Public flagging
* Individual show subscription
* CSS profiles
* Download audio
* Scrape from other public APIs

## Recommended way to install and run

1. Copy over the special `/.env.docker` to `/.env` and edit environment variables as appropriate
2. Use [Docker Compose](https://docs.docker.com/compose/) to install:

`docker-compose -p chaplin -f servers/docker/docker-compose.yml up -d`

3. Visit the site (http://localhost or add the vhost - by default http://dev.projectchaplin.com - to /etc/hosts).

## Development on Docker

Edits on the source repository will be propagated to the docker environment with this command instead of the above:

`docker-compose -p chaplin -f servers/docker/docker-compose.dev.yml up -d`

## Manual install

- Copy over `/.env.example` to `/.env` and edit environment variables as appropriate
- Check the Dockerfiles in `servers/docker` for installation instructions for each component.
- Compile the client-side JS in `src/php` by using `npm install` and `npm run build`.
- Ensure PHP has the bcmath, pdo and pdo_mysql (or whichever DB you use) extension.
- Install the PHP in `src/php` by using `composer install`.
- Install Nginx to serve PHP in `src/php/public`.
- Run listeners in `src/php/cli/cli.php` like: `php cli.php cli youtube`, `php cli.php cli convert` and `php cli.php cli vimeo`.
- Nginx configs are in `servers/nginx` and copy `servers/php-fpm/uploads.ini` to php-fpm's `conf.d`.
- SQL schema is in `db/`.
- Add appropriate hosts to `/etc/hosts` or use DNS.

## Join us!
We are currently looking for developers and designers to help this open source project.
If you're interested please contact me at chaplin@jolharg.com.

## Issues
For help, you can create an issue on the Github project:
https://github.com/danwdart/projectchaplin/issues
