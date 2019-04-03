<?
// Auto-included by Composer in composer.json to satisfy PHPStan

const EBOOKS_PER_PAGE = 12;
const SORT_NEWEST = 'newest';
const SORT_AUTHOR_ALPHA = 'author-alpha';

const GET = 0;
const POST = 1;
const COOKIE = 2;

const HTTP_VAR_INT = 0;
const HTTP_VAR_STR = 1;
const HTTP_VAR_BOOL = 2;
const HTTP_VAR_DEC = 3;

const SOURCE_PROJECT_GUTENBERG = 0;
const SOURCE_HATHI_TRUST = 1;
const SOURCE_WIKISOURCE = 2;
const SOURCE_INTERNET_ARCHIVE = 3;
const SOURCE_GOOGLE_BOOKS = 4;
const SOURCE_DP_OLS = 5;
const SOURCE_OTHER = 6;

const AVERAGE_READING_WORDS_PER_MINUTE = 275;

// No trailing slash on any of the below constants.
const SITE_URL =			'https://standardebooks.org';
const SITE_ROOT =			'/standardebooks.org';
const TEMPLATES_PATH =			SITE_ROOT . '/templates';
const REPOS_PATH =			SITE_ROOT . '/ebooks';

const GITHUB_SECRET_FILE_PATH =		SITE_ROOT . '/config/secrets/se-vcs-bot@github.com'; // Set in the GitHub organization global webhook settings.
const GITHUB_WEBHOOK_LOG_FILE_PATH =	'/var/log/local/webhooks-github.log'; // Must be writable by `www-data` Unix user.
const GITHUB_IGNORED_REPOS =		['tools', 'manual', 'web']; // If we get GitHub push requests featuring these repos, silently ignore instead of returning an error.
