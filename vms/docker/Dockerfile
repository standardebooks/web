FROM ubuntu:20.04

RUN apt-get update
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y composer php-fpm php-cli php-gd php-xml php-apcu php-mbstring php-intl apache2 apache2-utils libfcgi0ldbl task-spooler libaprutil1-dbd-mysql attr
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y sudo imagemagick openjdk-8-jre python3 pip calibre
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN pip install standardebooks

RUN sudo addgroup committers
RUN sudo adduser se
RUN sudo usermod -g committers se

RUN mkdir -p /standardebooks.org/web
RUN mkdir /var/log/local

RUN a2enmod headers expires ssl rewrite proxy proxy_fcgi authn_dbd authn_socache

# Disable opcaching for dynamic PHP reloading
RUN echo "opcache.enable=0" >> /etc/php/7.4/fpm/php.ini

EXPOSE 443
ENTRYPOINT ["/standardebooks.org/web/vms/docker/start-server.sh"]
