FROM ioft/armhf-ubuntu
WORKDIR /var/www
ADD apache/projectchaplin.conf /etc/apache2/sites-available/projectchaplin.conf
ADD install-docker.sh /tmp/install-docker.sh
EXPOSE 80 1337
ENV LC_ALL=en_GB.UTF-8
ENV LANG=en_GB.UTF-8
ENV REDIS_PORT=6379
ENV APPLICATION_ENV=development
ENV DEBIAN_FRONTEND=noninteractive
RUN /tmp/install-docker.sh
VOLUME ["/var/www"]
CMD /usr/sbin/apache2ctl -D FOREGROUND
