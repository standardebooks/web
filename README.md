# Installation

This repository only contains PHP source files, it doesn't contain configuration for running them on a web server.

PHP 7+ is required.

If you'd like to set up a development environment on your local machine, then you'll have to configure your own local web server to serve PHP files.

You'll also need to ensure the following:

- The path `/standardebooks.org/` exists and is the root of this project. (Configurable in `./lib/Constants.php`.)

- Your PHP installation must be configured to have `/standardebooks.org//lib/` in its include path.

- The URL `^/ebooks/([^\.]+?)/?$` must redirect to `/standardebooks.org/ebooks/ebook.php?url-path=$1`

- The URL `^/ebooks/([^\./]+?)/$` must redirect to `/standardebooks.org/ebooks/author.php?url-path=$1`

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

# Contributing

Before submitting design contributions, please discuss them with the Standard Ebooks lead. While we encourage discussion and contributions, we can't guarantee that unsoliticed design contributions will be accepted. You can open an issue to discuss potential design contributions with us before you begin.

## Code style

- Indent with tabs.

- Use single quotes (`'`) for strings, unless the string must contain a printable escape character (`"Foo\n"`).

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

- Where possible, include type hints for functions.

-   If using regex to parse HTML, use `|` as the regex delimiter instead of `/`.

    ````php
    preg_replace('|</foo>|ius', '</bar>', $text);
    ````

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
