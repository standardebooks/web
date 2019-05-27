# Installation

This repository only contains PHP source files, it doesn’t contain configuration for running them on a web server.

PHP 7+ is required.

If you’d like to set up a development environment on your local machine, then you’ll have to configure your own local web server to serve PHP files.

You’ll also need to ensure the following:

- The path `/standardebooks.org/` exists and is the root of this project. (Configurable in `./lib/Constants.php`.)

- Your PHP installation must be configured to have `/standardebooks.org/lib/` in its include path.

- [PHP short open tags](https://www.php.net/manual/en/ini.core.php#ini.short-open-tag) must be enabled.

- [PHP-APCu](http://php.net/manual/en/book.apcu.php), [PHP-intl](http://php.net/manual/en/book.intl.php), and [Composer](https://getcomposer.org/) must be installed. On Ubuntu this can be done with `sudo apt install php-apcu php-intl composer`.

-   Once Composer is installed, next install the SE Composer dependencies:

    ```bash
    cd /standardebooks.org/
    composer install
    ```

- The URL `^/ebooks/([^\./]+?)/$` must redirect to `/standardebooks.org/ebooks/author.php?url-path=$1`

- The URL `^/ebooks/([^\.]+?)/?$` must redirect to `/standardebooks.org/ebooks/ebook.php?url-path=$1`

- The URL `^/tags/([^\./]+?)/?$` must redirect to `/standardebooks.org/ebooks/index.php?tag=$1`

- The URL `/collections/([^\./]+?)/?$` must redirect to `/standardebooks.org/ebooks/index.php?collection=$1`

- Your web server should be configured to serve PHP files without the `.php` file extension. (I.e., your web server *internally* redirects `/foo/bar` to `/foo/bar.php`, if `/foo/bar.php` exists.)

# Filesystem layout

-   `/standardebooks.org/ebooks/` contains one directory per SE ebook, arranged in a flat hierarchy. These directories look like the URL-safe identifier for the ebook, end in `.git`, and are bare Git repos; they are the "source of truth" for SE ebooks.

    For example:

    ````
    /standardebooks.org/ebooks/algis-budrys_short-fiction.git/
    /standardebooks.org/ebooks/omar-khayyam_the-rubaiyat-of-omar-khayyam_edward-fitzgerald_edmund-dulac.git/
    ````

-   `/standardebooks.org/www/ebooks/` contains a nested hierarchy of deployed ebook files, that are read by the website for display and download. For example, we might have:

    ````
    /standardebooks.org/www/ebooks/maurice-leblanc/
    /standardebooks.org/www/ebooks/maurice-leblanc/the-hollow-needle/
    /standardebooks.org/www/ebooks/maurice-leblanc/the-hollow-needle/alexander-teixeira-de-mattos/
    /standardebooks.org/www/ebooks/maurice-leblanc/813/
    /standardebooks.org/www/ebooks/maurice-leblanc/813/alexander-teixeira-de-mattos/
    ````

    These directories contain the full ebook source, as if it was pulled from Git. (But they are not actual Git repositories.) Additionally each one contains a `./dist/` folder containing built ebook files for distribution.

    The website pulls all ebook information from what is contained in `/standardebooks.org/www/ebooks/`. It does not inspect `/standardebooks.org/ebooks/`. Therefore it is possible for one or the other to hold different catalogs if they become out of sync.

    To automatically populate your server with ebooks from https://github.com/standardebooks/, you can use sync-ebooks and deploy-ebook-to-www in the [scripts](scripts) directory. If you don't want to clone all ebooks, don't use sync-ebooks, and instead clone the books you want into `/standardebooks.org/ebooks` with `git clone --bare`. To clone a list of books, you can use `while IFS= read -r line; do git clone --bare "${line}"; done < urllist.txt`

# Testing

This repository includes [PHPStan](https://github.com/phpstan/phpstan) to statically analyze the codebase and [Safe PHP](https://github.com/thecodingmachine/safe) to replace old functions that don't throw exceptions.

To run PHPStan, execute:

```shell
$> <REPO-ROOT>/vendor/bin/phpstan analyse -c <REPO-ROOT>/config/phpstan/phpstan.neon
```

If run successfully, it should output `[OK] No errors`.

# Contributing

Before submitting design contributions, please discuss them with the Standard Ebooks lead. While we encourage discussion and contributions, we can’t guarantee that unsoliticed design contributions will be accepted. You can open an issue to discuss potential design contributions with us before you begin.

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

If you want to download the official binaries because Vagrant is not available in your distro's repository, because the version in the repository is too old or broken, or because you do not use Linux, you can download Vagrant [here](https://www.vagrantup.com/downloads.html)
and VirtualBox from [here.](https://www.virtualbox.org/wiki/Downloads)

After you have installed both, you can start and manage a VM running a server like this:

- `vagrant box add ubuntu/bionic64` downloads an [official Ubuntu 18.04 image](https://app.vagrantup.com/ubuntu/boxes/bionic64) (also known as a box in Vagrant terminology) for use as a base image in Vagrant VMs. This box is stored in `$HOME/.vagrant.d/boxes`.

- `vagrant run` will launch a VirtualBox VM running the server.

  * On its first invocation, or when the VM has been deleted, `vagrant up` will create a new VirtualBox VM in your standard Virtualbox machine directory (usually `$HOME/VirtualBox VMs`) using the previously added box as a base and configure it using the `Vagrantfile` and `scripts/provision.sh`. This means that the first time doing `vagrant run` will take significantly longer than following invocations because those simply start up the already created VM associated with VirtualBox.

  * Note that `vagrant run` will automatically download the box specified in the Vagrantfile (in this case `ubuntu/bionic64`), so you can skip `vagrant box add ubuntu/bionic64` if you want.

- The project root is mounted in the VM as `/standardebooks.org/`, so any changes you make in the repository will be reflected in the webroot inside VM. However, you may have to restart the server for it to pick up the changes. You can restart the entire VM by doing `vagrant reload` or you can use `vagrant ssh -c "sudo systemctl restart php7.2-fpm; sudo systemctl restart nginx;"` to restart php-fpm and nginx.

- You can run commands inside the VM with `vagrant ssh -c COMMAND` or launch an interactive SSH session with `vagrant ssh`.

- When you are done, you can shut down the VM by doing `vagrant halt`. To start it again, do `vagrant run`.

- To delete the project-local VirtualBox VM, run `vagrant destroy`. To get a fresh VM, for example, you can do `vagrant destroy` followed by `vagrant up`. Note that this does not delete any added boxes/base images added with `vagrant box add`.

## Some further notes

- The server runs on `127.0.0.1:8080` if you want to use a different port, change the `host: 8080` statement in the Vagrantfile to the desired port.

- You can list all locally installed boxes by doing `vagrant box list`.

- To completely delete a box, you can run `vagrant box remove ubuntu/bionic64`.

- To update the box, run `vagrant box update --box ubuntu/bionic64`. Note that this will not change the project-local VM. You will have to create a new VM based on the updated box by doing a `vagrant destroy` followed by a `vagrant up`. To remove old unused versions of boxes, run `vagrant box prune`.

- Check out scripts/provision.sh to see how the server is configured, the script is Vagrant-agnostic, so it should be helpful even if you don't want to use Vagrant.

- For further information, check out the [official Vagrant guide.](https://www.vagrantup.com/intro/index.html)
