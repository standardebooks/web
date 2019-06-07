# sync-ebooks

To use, call this script with the directory where your ebooks go as its last argument. For example `sync-ebooks /standardebooks.org/ebooks` or if you want to clone them into this repository `sync-ebooks ebooks`. If you want progress output, use -v, and if you want detailed git progress output use -vv. GitHub allows you to make 60 unauthenticated API requests per hour. If you use unauthenticated API requests for other things, this might not be enough, so to resolve that issue, you can create a new OAuth token at https://github.com/settings/tokens/new and pass it via the --token option. You don't need to give the token any permissions.

# deploy-ebook-to-www

To use, call this script with the directories of the books you want to deploy as its arguments. For example, to deploy all ebooks after using sync-ebooks, run `deploy-ebook-to-www /standardebooks.org/ebooks/*`. To deploy only The Time Machine by H.G Wells, you would run `deploy-ebook-to-www /standardebooks.org/ebooks/h-g-wells_the-time-machine`. Note that deploy-ebook-to-www assumes that your webroot is in /standardebooks.org/. To output progress information, use -v or --verbose.

The default group is se. to use a different one, specify it with the --group option. For instance, to use this script inside the included Vagrant VM, which uses the www-data group, use `deploy-ebook-to-www --group www-data /path/to/ebook`.
