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

# Vagrant

You can also set up a development server running Ubuntu 18.04 with nginx and php-fpm by using the included Vagrantfile in the project root. To do so, first install Vagrant and VirtualBox.

Both are available as packages in several major distributions, including Arch, Debian, Fedora, Gentoo, Ubuntu, and NixOS among others. Note that [the official docs advise against installing from distribution repositories](https://www.vagrantup.com/intro/getting-started/install.html) due to potentially old versions or incorrect dependencies.

If you want to download the official binaries because Vagrant is not available in your distro’s repository, because the version in the repository is too old or broken, or because you do not use Linux, you can download Vagrant [here](https://www.vagrantup.com/downloads.html)
and VirtualBox from [here.](https://www.virtualbox.org/wiki/Downloads)

After you have installed both, you can start and manage a VM running a server like this:

- `vagrant box add ubuntu/bionic64` downloads an [official Ubuntu 18.04 image](https://app.vagrantup.com/ubuntu/boxes/bionic64) (also known as a box in Vagrant terminology) for use as a base image in Vagrant VMs. This box is stored in `$HOME/.vagrant.d/boxes`.

- `vagrant run` will launch a VirtualBox VM running the server. (Note: newer versions of Vagrant, such as the one found on Arch, use `up` instead of `run`.)

  * On its first invocation, or when the VM has been deleted, `vagrant up` will create a new VirtualBox VM in your standard Virtualbox machine directory (usually `$HOME/VirtualBox VMs`) using the previously added box as a base and configure it using the `Vagrantfile` and `scripts/provision.sh`. This means that the first time doing `vagrant run` will take significantly longer than following invocations because those simply start up the already created VM associated with VirtualBox.

  * Note that `vagrant run` will automatically download the box specified in the Vagrantfile (in this case `ubuntu/bionic64`), so you can skip `vagrant box add ubuntu/bionic64` if you want.

- The project root is mounted in the VM as `/standardebooks.org/`, so any changes you make in the repository will be reflected in the webroot inside VM. However, you may have to restart the server for it to pick up the changes. You can restart the entire VM by doing `vagrant reload` or you can use `vagrant ssh -c "sudo systemctl restart php7.2-fpm; sudo systemctl restart nginx;"` to restart php-fpm and nginx.

- You can run commands inside the VM with `vagrant ssh -c COMMAND` or launch an interactive SSH session with `vagrant ssh`.

- When you are done, you can shut down the VM by doing `vagrant halt`. To start it again, do `vagrant run`.

- To delete the project-local VirtualBox VM, run `vagrant destroy`. To get a fresh VM, for example, you can do `vagrant destroy` followed by `vagrant up`. Note that this does not delete any added boxes/base images added with `vagrant box add`.

## Some further notes

- The Vagrant script will install [se](https://standardebooks.org/tools) by default. If you don’t want that (it pulls in quite a few dependencies), remove the `se-tools` argument in Vagrantfile.

- `se`, if installed in the VM, and /standardebooks.org/scripts are in the VMs path. This means you can easily use them with `vagrant ssh -c` like this: `vagrant ssh -c "sync-ebooks -vv /standardebooks.org/ebooks; deploy-ebook-to-www -v --group www-data /standardebooks.org/ebooks/*"`, which would populate the test server with all available SE ebooks.

- It is safe to re-run the provision script if you did not change the nginx or php configuration files (change the files in the provision script and re-provision instead), so you can use `vagrant up --provision` or `vagrant reload --provision to update the VM, including se-tools and epubcheck, without having to delete it.

- The server runs on `127.0.0.1:8080` if you want to use a different port, change the `host: 8080` statement in the Vagrantfile to the desired port.

- You can list all locally installed boxes by doing `vagrant box list`.

- To completely delete a box, you can run `vagrant box remove ubuntu/bionic64`.

- To update the box, run `vagrant box update --box ubuntu/bionic64`. Note that this will not change the project-local VM. You will have to create a new VM based on the updated box by doing a `vagrant destroy` followed by a `vagrant up`. To remove old unused versions of boxes, run `vagrant box prune`.

- Check out scripts/provision.sh to see how the server is configured, the script is Vagrant-agnostic, so it should be helpful even if you don’t want to use Vagrant.

- For further information, check out the [official Vagrant guide.](https://www.vagrantup.com/intro/index.html)
