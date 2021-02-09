FROM ubuntu:20.04

RUN apt-get update
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y composer php-fpm php-cli php-gd php-xml php-apcu php-mbstring php-intl apache2 apache2-utils libfcgi0ldbl task-spooler
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN mkdir -p /standardebooks.org/web/config/ssl
RUN mkdir /var/log/local

RUN openssl req -x509 -nodes -days 99999 -newkey rsa:4096 -subj "/CN=standardebooks.test" -keyout /standardebooks.org/web/config/ssl/standardebooks.test.key -sha256 -out /standardebooks.org/web/config/ssl/standardebooks.test.crt

RUN a2enmod headers expires ssl rewrite proxy proxy_fcgi

# Disable opcaching for dynamic PHP reloading
RUN echo "opcache.enable=0\n" >> /etc/php/7.4/fpm/php.ini

EXPOSE 443
ENTRYPOINT ["/standardebooks.org/web/scripts/docker/start-server.sh"]
