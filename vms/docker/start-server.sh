#!/bin/sh

# Generate SSL certificate if it doesn't exist
if [ ! -f /standardebooks.org/web/config/ssl/standardebooks.test.crt ]; then
	openssl req -x509 -nodes -days 99999 -newkey rsa:4096 -subj "/CN=standardebooks.test" -keyout /standardebooks.org/web/config/ssl/standardebooks.test.key -sha256 -out /standardebooks.org/web/config/ssl/standardebooks.test.crt
fi

# Install PHP dependencies
cd /standardebooks.org/web
composer install

# Create symlinks for web server configuration
ln -s /standardebooks.org/web/config/apache/standardebooks.test.conf /etc/apache2/sites-available/
ln -s /standardebooks.org/web/config/php/fpm/standardebooks.test.ini /etc/php/*/cli/conf.d/
ln -s /standardebooks.org/web/config/php/fpm/standardebooks.test.ini /etc/php/*/fpm/conf.d/
ln -s /standardebooks.org/web/config/php/fpm/standardebooks.test.conf /etc/php/*/fpm/pool.d/

# Enable web server configuration
a2ensite standardebooks.test

# Restart services to load new configuration
service apache2 restart
service php7.4-fpm restart

# Keep the server available by holding open the container
tail -f /dev/null
