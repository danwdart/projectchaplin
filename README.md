# Project Chaplin
an open source, self-hosted video sharing service

## Current features
* AGPLv3 License
* No intrusive adverts
* No restrictions on content by country, IP or government
* Video downloading as standard
* HTML5 WebM as standard
* Live streaming (pluginless)
* Brightness/Contrast controls
* Easter eggs?

## Future features
* No staff blocking, auto-blocking only on public demand
* REST APIs
* Public tagging
* Public flagging
* Individual show subscription
* CSS profiles
* Download audio
* Scrape from other public APIs

## Recommended way to install
Try using Docker for a fast install:

`docker run --link mysql:mysql --link redis:redis --link rabbitmq:rabbitmq -p 80:80 -p 1337:1337 -d --rm -v $PWD:/var/www kathiedart/projectchaplin`

This will install the dependencies and let you use your local pull as a volume. To upgrade at any time just git pull.

## Join us!
We are currently looking for developers and designers to help this open source project.
If you're interested please contact me at chaplin@kathiedart.co.uk.

## Issues
For help, you can create an issue on the Github project:
https://github.com/kathiedart/projectchaplin/issues

### Remember
Have fun!
