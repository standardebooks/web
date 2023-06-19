<?
// Auto-included by Composer in composer.json to satisfy PHPStan
use function Safe\define;
use function Safe\gmdate;
use function Safe\strtotime;

const SITE_STATUS_LIVE = 		'live';
const SITE_STATUS_DEV =			'dev';

define('SITE_STATUS', get_cfg_var('se.site_status') ?: SITE_STATUS_DEV); // Set in the PHP INI configuration for both CLI and FPM. Have to use define() and not const so we can use a function.

// No trailing slash on any of the below constants.
if(SITE_STATUS == SITE_STATUS_LIVE){
	define('SITE_DOMAIN', 'standardebooks.org');
}
else{
	define('SITE_DOMAIN', 'standardebooks.test');
}

const SITE_URL =			'https://' . SITE_DOMAIN;
const SITE_ROOT =			'/standardebooks.org';
const WEB_ROOT =			SITE_ROOT . '/web/www';
const REPOS_PATH =			SITE_ROOT . '/ebooks';
const TEMPLATES_PATH =			SITE_ROOT . '/web/templates';
const MANUAL_PATH =			WEB_ROOT . '/manual';
const EBOOKS_DIST_PATH =		WEB_ROOT . '/ebooks/';
const COVER_ART_UPLOAD_PATH = WEB_ROOT . '/images/uploads/';

const DATABASE_DEFAULT_DATABASE = 	'se';
const DATABASE_DEFAULT_HOST = 		'localhost';

const EBOOKS_PER_PAGE = 12;
const SORT_NEWEST = 'newest';
const SORT_AUTHOR_ALPHA = 'author-alpha';
const SORT_READING_EASE = 'reading-ease';
const SORT_LENGTH = 'length';

const COVER_THUMBNAIL_SIZE = 300;

const CAPTCHA_IMAGE_HEIGHT = 72;
const CAPTCHA_IMAGE_WIDTH = 230;
const NO_REPLY_EMAIL_ADDRESS = 'admin@standardebooks.org';
const ADMIN_EMAIL_ADDRESS = 'admin@standardebooks.org';
const EDITOR_IN_CHIEF_EMAIL_ADDRESS = 'alex@standardebooks.org';
const EDITOR_IN_CHIEF_NAME = 'Alex Cabal';
define('EMAIL_SMTP_USERNAME', get_cfg_var('se.secrets.postmark.username'));
const EMAIL_SMTP_HOST = 'smtp.postmarkapp.com';
const EMAIL_POSTMARK_STREAM_BROADCAST = 'the-standard-ebooks-newsletter';

const REST = 0;
const WEB = 1;

const GET = 'GET';
const POST = 'POST';
const COOKIE = 'COOKIE';
const SESSION = 'SESSION';

const HTTP_VAR_INT = 0;
const HTTP_VAR_STR = 1;
const HTTP_VAR_BOOL = 2;
const HTTP_VAR_DEC = 3;
const HTTP_VAR_ARRAY = 4;

const HTTP_GET = 0;
const HTTP_POST = 1;
const HTTP_PATCH = 2;
const HTTP_PUT = 3;
const HTTP_DELETE = 4;
const HTTP_HEAD = 5;

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

const PAYMENT_CHANNEL_FA = 0;

const FA_FEE_PERCENT = 0.87;

const SE_SUBJECTS = ['Adventure', 'Autobiography', 'Biography', 'Children’s', 'Comedy', 'Drama', 'Fantasy', 'Fiction', 'Horror', 'Memoir', 'Mystery', 'Nonfiction', 'Philosophy', 'Poetry', 'Satire', 'Science Fiction', 'Shorts', 'Spirituality', 'Tragedy', 'Travel'];

const GITHUB_IGNORED_REPOS =		['tools', 'manual', 'web']; // If we get GitHub push requests featuring these repos, silently ignore instead of returning an error.

const GITHUB_WEBHOOK_LOG_FILE_PATH =	'/var/log/local/webhooks-github.log'; // Must be writable by `www-data` Unix user.
const POSTMARK_WEBHOOK_LOG_FILE_PATH =	'/var/log/local/webhooks-postmark.log'; // Must be writable by `www-data` Unix user.
const ZOHO_WEBHOOK_LOG_FILE_PATH =	'/var/log/local/webhooks-zoho.log'; // Must be writable by `www-data` Unix user.
const DONATIONS_LOG_FILE_PATH =		'/var/log/local/donations.log'; // Must be writable by `www-data` Unix user.

define('PD_YEAR', intval(gmdate('Y')) - 96);
define('PD_STRING', 'January 1, ' . (PD_YEAR + 1));

define('DONATION_HOLIDAY_ALERT_ON', time() > strtotime('November 15, ' . gmdate('Y'))  || time() < strtotime('January 7, ' . gmdate('Y')));
define('DONATION_ALERT_ON', DONATION_HOLIDAY_ALERT_ON || rand(1, 4) == 2);
define('DONATION_DRIVE_ON', false);
define('DONATION_DRIVE_COUNTER_ON', false);

