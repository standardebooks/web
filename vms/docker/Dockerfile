FROM ubuntu:22.04

# Avoid user interaction with tzdata when installing packages
ENV DEBIAN_FRONTEND=noninteractive

# Install dependencies and required PHP extensions
RUN apt-get update && \
    apt-get install -y --no-install-recommends tzdata apt-utils composer \
    php8.1-fpm php8.1-cli php8.1-gd php8.1-xml php8.1-apcu \
    php8.1-mbstring php8.1-intl php8.1-curl php8.1-zip apache2 apache2-utils \
    libfcgi0ldbl task-spooler libaprutil1-dbd-mysql attr libapache2-mod-xsendfile && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install other necessary packages
RUN apt-get update && \
    apt-get install -y --no-install-recommends sudo imagemagick openjdk-8-jre-headless python3 python3-pip calibre && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install Python package for standardebooks
RUN pip install standardebooks

# Create group and user for standardebooks
RUN groupadd committers && \
    useradd -m -g committers se && \
    passwd -d se # Disable password for user 'se'

# Create necessary directories
RUN mkdir -p /standardebooks.org/web && \
    mkdir /var/log/local

# Enable Apache modules
RUN a2enmod headers expires ssl rewrite proxy proxy_fcgi authn_dbd authn_socache

# Disable opcode caching for dynamic PHP reloading and update ini file for PHP 8.1
RUN echo "opcache.enable=0" >> /etc/php/8.1/fpm/php.ini

# Expose port for https
EXPOSE 443

# Set entrypoint
ENTRYPOINT ["/standardebooks.org/web/vms/docker/start-server.sh"]
