<?
// Auto-included by Composer in composer.json to satisfy PHPStan
use Safe\DateTime;
use function Safe\define;

$now = new DateTime('now', new DateTimeZone('UTC'));
$nowPd = new DateTime('now', new DateTimeZone('America/Juneau')); // Latest continental US time zone

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
const COVER_ART_UPLOAD_PATH =		'/images/cover-uploads/';

const DATABASE_DEFAULT_DATABASE = 	'se';
const DATABASE_DEFAULT_HOST = 		'localhost';

const EBOOKS_PER_PAGE = 12;
const SORT_NEWEST = 'newest';
const SORT_AUTHOR_ALPHA = 'author-alpha';
const SORT_READING_EASE = 'reading-ease';
const SORT_LENGTH = 'length';

const ARTWORK_THUMBNAIL_HEIGHT = 350;
const ARTWORK_THUMBNAIL_WIDTH = 350;
const ARTWORK_PER_PAGE = 20;
const ARTWORK_MAX_STRING_LENGTH = 250;
const ARTWORK_MAX_TAGS = 15;
const ARTWORK_IMAGE_MINIMUM_WIDTH = 300;
const ARTWORK_IMAGE_MINIMUM_HEIGHT = 300;
const SORT_COVER_ARTWORK_CREATED_NEWEST = 'created-newest';
const SORT_COVER_ARTIST_ALPHA = 'artist-alpha';
const SORT_COVER_ARTWORK_COMPLETED_NEWEST = 'completed-newest';

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

const AVERAGE_READING_WORDS_PER_MINUTE = 275;

const PAYMENT_CHANNEL_FA = 0;

const FA_FEE_PERCENT = 0.87;

const SE_SUBJECTS = ['Adventure', 'Autobiography', 'Biography', 'Children’s', 'Comedy', 'Drama', 'Fantasy', 'Fiction', 'Horror', 'Memoir', 'Mystery', 'Nonfiction', 'Philosophy', 'Poetry', 'Satire', 'Science Fiction', 'Shorts', 'Spirituality', 'Tragedy', 'Travel'];

const GITHUB_IGNORED_REPOS =		['tools', 'manual', 'web']; // If we get GitHub push requests featuring these repos, silently ignore instead of returning an error.

const GITHUB_WEBHOOK_LOG_FILE_PATH =	'/var/log/local/webhooks-github.log'; // Must be writable by `www-data` Unix user.
const POSTMARK_WEBHOOK_LOG_FILE_PATH =	'/var/log/local/webhooks-postmark.log'; // Must be writable by `www-data` Unix user.
const ZOHO_WEBHOOK_LOG_FILE_PATH =	'/var/log/local/webhooks-zoho.log'; // Must be writable by `www-data` Unix user.
const DONATIONS_LOG_FILE_PATH =		'/var/log/local/donations.log'; // Must be writable by `www-data` Unix user.
const ARTWORK_UPLOADS_LOG_FILE_PATH =	'/var/log/local/artwork-uploads.log'; // Must be writable by `www-data` Unix user.

define('PD_YEAR', intval($nowPd->format('Y')) - 96);
define('PD_STRING', 'January 1, ' . (PD_YEAR + 1));

define('DONATION_HOLIDAY_ALERT_ON', $now > new DateTime('November 15, ' . $now->format('Y'), new DateTimeZone('UTC')) || $now < new DateTime('January 7, ' . $now->add(new DateInterval('P1Y'))->format('Y'), new DateTimeZone('UTC')));
define('DONATION_ALERT_ON', DONATION_HOLIDAY_ALERT_ON || rand(1, 4) == 2);

// Controls the progress bar donation dialog
const DONATION_DRIVE_ON = true;
const DONATION_DRIVE_START = 'December 11, 2023 00:00:00 America/New_York';
const DONATION_DRIVE_END = 'January 7, 2024 23:59:00 America/New_York';

// Controls the countdown donation dialog
const DONATION_DRIVE_COUNTER_ON = false;
const DONATION_DRIVE_COUNTER_START = 'May 2, 2022 00:00:00 America/New_York';
const DONATION_DRIVE_COUNTER_END = 'May 8, 2022 23:59:00 America/New_York';
