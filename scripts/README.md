# sync-ebooks

To use, call this script with the directory where your ebooks go as its last argument. For example `sync-ebooks /standardebooks.org/ebooks`, or if you want to clone them into this repository, `sync-ebooks ebooks`. If you want progress output, use `-v`, and if you want detailed Git progress output use `-vv`. GitHub allows you to make 60 unauthenticated API requests per hour. If you use unauthenticated API requests for other things, this might not be enough, so to resolve that issue, you can create a new OAuth token at `https://github.com/settings/tokens/new` and pass it via the `--token` option. You don’t need to give the token any permissions.

# deploy-ebook-to-www

To use, call this script with the directories of the books you want to deploy as its arguments. For example, to deploy all ebooks after using sync-ebooks, run `deploy-ebook-to-www /standardebooks.org/ebooks/*`. To deploy only The Time Machine by H. G. Wells, you would run `deploy-ebook-to-www /standardebooks.org/ebooks/h-g-wells_the-time-machine`. To output progress information, use `-v` or `--verbose`.

The default web root is `/standardebooks.org/web/www`. If it is located elsewhere, specify it with the `--webroot` option. For instance, `deploy-ebook-to-www --webroot /var/www/html /path/to/ebook`. Note that there will be php errors if the Git repositories are not in the ebook directory immediately in the web root. Either keep them there or create a symlink.

The default group is `se`. to use a different one, specify it with the `--group` option.

The default URL is `https://standardebooks.org`. To change it, use the `--weburl` option. For example, `deploy-ebook-to-www --weburl "http://localhost:8080"`. This option will cause `deploy-ebook-to-www` to use the specified URL in the generated OPDS and RSS files. Care should be taken however; the URL `https://standardebooks.org` is hard-coded in a few places, even when `SITE_URL` is changed to a custom URL, so for testing, it may be more convenient to simply use `/etc/hosts` or a similar mechanism to resolve `standardebooks.org` to `127.0.0.1`.
