# Installation

PHP 7+ is required.

## Installing on Ubuntu 20.04 (Focal)

```shell
# Install Apache, PHP, PHP-FPM, and various other dependencies.
sudo apt install -y git composer php-fpm php-cli php-gd php-xml php-apcu php-mbstring php-intl apache2 apache2-utils libfcgi0ldbl task-spooler

# Create the site root and logs root and clone this repo into it.
sudo mkdir /standardebooks.org/
sudo chown $(whoami): /standardebooks.org
sudo mkdir /var/log/local/
cd /standardebooks.org/
git clone https://github.com/standardebooks/web/

# Install dependencies using Composer.
cd /standardebooks.org/web/
composer install

# Add standardebooks.test to your hosts file.
echo -e "127.0.0.1\tstandardebooks.test" | sudo tee -a /etc/hosts

# Create a self-signed SSL certificate for use with the local web site installation.
openssl req -x509 -nodes -days 99999 -newkey rsa:4096 -subj "/CN=standardebooks.test" -keyout /standardebooks.org/web/config/ssl/standardebooks.test.key -sha256 -out /standardebooks.org/web/config/ssl/standardebooks.test.crt

# Enable the necessary Apache modules.
sudo a2enmod headers expires ssl rewrite proxy proxy_fcgi

# Link and enable the SE Apache configuration file.
sudo ln -s /standardebooks.org/web/config/apache/standardebooks.test.conf /etc/apache2/sites-available/
sudo a2ensite standardebooks.test
sudo systemctl restart apache2.service

# Link and enable the SE PHP-FPM pool.
sudo ln -s /standardebooks.org/web/config/php/fpm/standardebooks.org.ini /etc/php/*/cli/conf.d/
sudo ln -s /standardebooks.org/web/config/php/fpm/standardebooks.org.ini /etc/php/*/fpm/conf.d/
sudo ln -s /standardebooks.org/web/config/php/fpm/standardebooks.test.conf /etc/php/*/fpm/pool.d/
sudo systemctl restart "php*-fpm.service"

# Download the OPDS index template
wget -O /standardebooks.org/web/www/opds/index.xml https://standardebooks.org/opds
```

If everything went well you should now be able to open your web browser and visit `https://standardebooks.test/`. However, you won’t see any ebooks if you visit `https://standardebooks.test/ebooks/`. To install some ebooks, first you have to clone their source from GitHub, then deploy them to your local website using the `./scripts/deploy-ebook-to-www` script:

```shell
# First, install the SE toolset, which will make the `se build` command-line executable available to the `deploy-ebook-to-www` script:
# https://standardebooks.org/tools

# Once the toolset is installed, clone a book and deploy it to your local SE site:
mkdir /standardebooks.org/ebooks/
cd /standardebooks.org/ebooks/
git clone --bare https://github.com/standardebooks/david-lindsay_a-voyage-to-arcturus
/standardebooks.org/web/scripts/deploy-ebook-to-www david-lindsay_a-voyage-to-arcturus
```

If everything went well, `https://standardebooks.test/ebooks/` will show the one ebook you deployed.

## Installation using Docker

We provide a Dockerfile for testing code changes. You can build an image with:

```shell
docker build . -t standardebooks -f vms/docker/Dockerfile
```

Then run the built image with:

```shell
docker run -dp 443:443 -v "$(pwd):/standardebooks.org/web" standardebooks:latest
```

The site will now be available at `https://localhost/`, although as it’s a self-signed certificate you’ll need to accept whatever browser security warnings come up.

# Filesystem layout

-   `/standardebooks.org/ebooks/` contains one directory per SE ebook, arranged in a flat hierarchy. These directories look like the URL-safe identifier for the ebook, end in `.git`, and are bare Git repos; they are the “source of truth” for SE ebooks.

    For example:

    ````
    /standardebooks.org/ebooks/algis-budrys_short-fiction.git/
    /standardebooks.org/ebooks/omar-khayyam_the-rubaiyat-of-omar-khayyam_edward-fitzgerald_edmund-dulac.git/
    ````

-   `/standardebooks.org/web/www/ebooks/` contains a nested hierarchy of deployed ebook files, that are read by the website for display and download. For example, we might have:

    ````
    /standardebooks.org/web/www/ebooks/maurice-leblanc/
    /standardebooks.org/web/www/ebooks/maurice-leblanc/the-hollow-needle/
    /standardebooks.org/web/www/ebooks/maurice-leblanc/the-hollow-needle/alexander-teixeira-de-mattos/
    /standardebooks.org/web/www/ebooks/maurice-leblanc/813/
    /standardebooks.org/web/www/ebooks/maurice-leblanc/813/alexander-teixeira-de-mattos/
    ````

    These directories contain the full ebook source, as if it was pulled from Git. (But they are not actual Git repositories.) Additionally each one contains a `./downloads/` folder containing built ebook files for distribution.

    The website pulls all ebook information from what is contained in `/standardebooks.org/web/www/ebooks/`. It does not inspect `/standardebooks.org/ebooks/`. Therefore it is possible for one or the other to hold different catalogs if they become out of sync.

    To automatically populate your server with ebooks from https://github.com/standardebooks/, you can use `sync-ebooks` and `deploy-ebook-to-www` in the [scripts](scripts) directory. If you don’t want to clone all ebooks, don’t use `sync-ebooks`, and instead clone the books you want into `/standardebooks.org/ebooks` with `git clone --bare`. To clone a list of books, you can use `while IFS= read -r line; do git clone --bare "${line}"; done < urllist.txt`

-   `/standardebooks.org/web/www/manual/` contains the *compiled* [Standard Ebooks Manual of Style](https://github.com/standardebooks/manual). Because it is compiled from other sources, the distributable PHP files are not included in this repo. To include the manual, clone the SEMOS repo and follow the instructions in its readme to compile it into `/standardebooks.org/web/www/manual/x.y.z/`.

# Testing

This repository includes [PHPStan](https://github.com/phpstan/phpstan) to statically analyze the codebase and [Safe PHP](https://github.com/thecodingmachine/safe) to replace old functions that don’t throw exceptions.

To run PHPStan, execute:

```shell
$> <REPO-ROOT>/vendor/bin/phpstan analyse -c <REPO-ROOT>/config/phpstan/phpstan.neon
```

If run successfully, it should output `[OK] No errors`.

# Contributing

Before submitting design contributions, please discuss them with the Standard Ebooks lead. While we encourage discussion and contributions, we can’t guarantee that unsoliticed design contributions will be accepted. You can open an issue to discuss potential design contributions with us before you begin.

## Help wanted

- Creating a search bar for the SE Manual of Style.

## PHP code style

- Indent with tabs.

-   Use single quotes for strings, unless the string must contain a printable escape character.

    ````php
    $foo = 'baz';
    $bar = "\tbaz\n";
    ````

-   When composing strings always use string concatenation. Don’t evaluate PHP variables inside strings.

    Good:

    ````php
    $foo = 'bar' . $fizz . 'buzz';
    ````

    Bad:

    ````php
    $foo = "bar$fizzbuzz";
    $foo = "bar{$fizz}buzz";
    ````

-   Variable names are in camelCase.

    ````php
    $fooBar = 'baz';
    ````

-   Object members are in PascalCase.

    ````php
    $myObject->FooBar = 'baz';
    ````

- Output to HTML is done using `<?= $var ?>`, not `print($var)` or any other printing function.

- Check for `null` using `===` and `!==`.

- Where possible, include type hints for functions. Due to PHP limitations this may not always be possible, for example in cases where `null` may be passed or returned.

-   If using regex to parse HTML, use `|` as the regex delimiter instead of `/`.

    ````php
    preg_replace('|</foo>|ius', '</bar>', $text);
    ````

-   Omit closing PHP tags in files that are pure PHP.

-   Language constructs that can optionally have curly braces (like `if`) should always have curly braces.

    Good:

    ````php
    if(true){
        print('Foo!');
    }
    else{
        print('Bar!');
    }
    ````

    Bad:

    ````php
    if(true)
        print('Foo!');
    else
        print('Bar!');
    ````
