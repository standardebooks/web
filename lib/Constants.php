<?
// Auto-included by Composer in composer.json to satisfy PHPStan
use function Safe\define;
use function Safe\strtotime;

const EBOOKS_PER_PAGE = 12;
const SORT_NEWEST = 'newest';
const SORT_AUTHOR_ALPHA = 'author-alpha';
const SORT_READING_EASE = 'reading-ease';
const SORT_LENGTH = 'length';

const GET = 0;
const POST = 1;
const COOKIE = 2;

const HTTP_VAR_INT = 0;
const HTTP_VAR_STR = 1;
const HTTP_VAR_BOOL = 2;
const HTTP_VAR_DEC = 3;
const HTTP_VAR_ARRAY = 4;

const VIEW_GRID = 'grid';
const VIEW_LIST = 'list';

const SOURCE_PROJECT_GUTENBERG = 0;
const SOURCE_HATHI_TRUST = 1;
const SOURCE_WIKISOURCE = 2;
const SOURCE_INTERNET_ARCHIVE = 3;
const SOURCE_GOOGLE_BOOKS = 4;
const SOURCE_OTHER = 6;
const SOURCE_PROJECT_GUTENBERG_CANADA = 7;
const SOURCE_PROJECT_GUTENBERG_AUSTRALIA = 8;
const SOURCE_FADED_PAGE = 9;

const AVERAGE_READING_WORDS_PER_MINUTE = 275;

define('PD_YEAR', intval(gmdate('Y')) - 96);

define('DONATION_HOLIDAY_ALERT_ON', time() > strtotime('November 15, ' . gmdate('Y'))  || time() < strtotime('January 7, ' . gmdate('Y')));
define('DONATION_ALERT_ON', rand(1, 4) == 2);

// No trailing slash on any of the below constants.
const SITE_URL =			'https://standardebooks.org';
const SITE_ROOT =			'/standardebooks.org';
const WEB_ROOT =			SITE_ROOT . '/web/www';
const REPOS_PATH =			SITE_ROOT . '/ebooks';
const TEMPLATES_PATH =			SITE_ROOT . '/web/templates';
const MANUAL_PATH =			WEB_ROOT . '/manual';
const EBOOKS_DIST_PATH =		WEB_ROOT . '/ebooks/';

const GITHUB_SECRET_FILE_PATH =		SITE_ROOT . '/config/secrets/se-vcs-bot@github.com'; // Set in the GitHub organization global webhook settings.
const GITHUB_WEBHOOK_LOG_FILE_PATH =	'/var/log/local/webhooks-github.log'; // Must be writable by `www-data` Unix user.

// If we get GitHub push requests featuring these repos, silently ignore instead of returning an error.
const GITHUB_IGNORED_REPOS =		['tools', 'manual', 'web'];
