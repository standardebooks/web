#!/usr/bin/env bash

apt-get update -y
apt-get install -y nginx php-fpm php-apcu php-intl php-mbstring\
	php-xml git

cat << "EOF" > /etc/php/7.2/fpm/conf.d/99-standardebooks.ini
include_path = .:/standardebooks.org/lib
short_open_tag = On
extension = apcu
extension = intl
#display_errors = On
#display_startup_errors = On
EOF

cat << "EOF" > /etc/nginx/sites-available/default
server {
	listen 80 default_server;
	listen [::]:80 default_server;

	root /standardebooks.org/www;
	index index.php index.html index.htm index.nginx-debian.html;

	server_name _;

	location / {
		try_files $uri $uri/ @extensionless-php;
	}

	location ~ \.php$ {
		include snippets/fastcgi-php.conf;
		fastcgi_pass unix:/run/php/php7.2-fpm.sock;
	}

	location ~ /\.ht {
		deny all;
	}

	location @extensionless-php {
    		rewrite ^(.*)$ $1.php last;
	}

	rewrite ^/ebooks/([^\./]+?)/$ /ebooks/author.php?url-path=$1;
	rewrite ^/ebooks/([^\.]+?)/?$ /ebooks/ebook.php?url-path=$1;
	rewrite ^/tags/([^\./]+?)/?$ /ebooks/index.php?tag=$1;
	rewrite ^/collections/([^\./]+?)/?$ /ebooks/index.php?collection=$1;
}
EOF

systemctl enable php7.2-fpm
systemctl enable nginx
systemctl restart php7.2-fpm
systemctl restart nginx
