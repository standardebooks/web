FROM ubuntu:22.04

# Install required packages
RUN apt update
RUN DEBIAN_FRONTEND=noninteractive apt install -y composer php-fpm php-cli php-gd php-xml php-apcu php-mbstring php-intl php-curl php-zip apache2 apache2-utils libfcgi0ldbl task-spooler libaprutil1-dbd-mysql attr libapache2-mod-xsendfile sudo imagemagick openjdk-8-jre python3 pip calibre

# Install Python package for standardebooks
RUN pip install standardebooks

# Create group and user for standardebooks
RUN sudo addgroup committers
RUN sudo adduser se
RUN sudo usermod -g committers se

# Create necessary directories
RUN mkdir -p /standardebooks.org/web
RUN mkdir /var/log/local

# Enable Apache modules
RUN a2enmod headers expires ssl rewrite proxy proxy_fcgi authn_dbd authn_socache

# Disable opcode caching to enable dynamic PHP reloading
RUN echo "opcache.enable=0" | tee --append /etc/php/*/fpm/php.ini

# Expose port for https
EXPOSE 443

# Set entrypoint
ENTRYPOINT ["/standardebooks.org/web/vms/docker/start-server.sh"]
