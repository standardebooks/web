#!/bin/sh

# Generate SSL certificate if it doesn't exist
if [ ! -f /standardebooks.org/web/config/ssl/standardebooks.test.crt ]; then
	openssl req -x509 -nodes -days 99999 -newkey rsa:4096 -subj "/CN=standardebooks.test" -keyout /standardebooks.org/web/config/ssl/standardebooks.test.key -sha256 -out /standardebooks.org/web/config/ssl/standardebooks.test.crt
fi

cd /standardebooks.org/web

# Update to required PHP version here if necessary

# Run Composer install.
# Note that the PHP version should be compatible with the packages in the composer.lock file.
composer install

# Create symlink after checking if it already exists or removing the existing one
create_symlink() {
	target=$1
	link=$2

	if [ -L "$link" ]; then # Check if symlink already exists.
		echo "Symlink $link already exists; skipping."
	else
		ln -s "$target" "$link"
	fi
}

create_symlink /standardebooks.org/web/config/apache/standardebooks.test.conf /etc/apache2/sites-available/standardebooks.test.conf
create_symlink /standardebooks.org/web/config/php/fpm/standardebooks.org.ini /etc/php/8.1/cli/conf.d/standardebooks.org.ini
create_symlink /standardebooks.org/web/config/php/fpm/standardebooks.org.ini /etc/php/8.1/fpm/conf.d/standardebooks.org.ini
create_symlink /standardebooks.org/web/config/php/fpm/standardebooks.test.conf /etc/php/8.1/fpm/pool.d/standardebooks.test.conf

# Enable site only if it's not already enabled
if ! a2query -s standardebooks.test > /dev/null; then
	a2ensite standardebooks.test
fi

# Start or restart services
service apache2 start || service apache2 reload
service php8.1-fpm restart || service php8.1-fpm start

# Keep the server available by holding open the container
tail -f /dev/null
