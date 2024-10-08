<?
// Auto-included by Composer in composer.json to satisfy PHPStan
use Safe\DateTimeImmutable;
use function Safe\define;

$now = new DateTimeImmutable();
$nowPd = new DateTimeImmutable('now', new DateTimeZone('America/Juneau')); // Latest continental US time zone

const SITE_STATUS_LIVE = 		'live';
const SITE_STATUS_DEV =			'dev';

define('SITE_STATUS', get_cfg_var('app.site_status') ?: SITE_STATUS_DEV); // Set in the PHP INI configuration for both CLI and FPM. Have to use define() and not const so we can use a function.

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

const EBOOKS_IDENTIFIER_PREFIX =	'url:https://standardebooks.org/ebooks/';

const DATABASE_DEFAULT_DATABASE = 	'se';
const DATABASE_DEFAULT_HOST = 		'localhost';

const EBOOKS_PER_PAGE = 12;
const EBOOKS_MAX_STRING_LENGTH = 250;
const EBOOKS_MAX_LONG_STRING_LENGTH = 500;
const EBOOK_SINGLE_PAGE_SIZE_WARNING = 3 *1024 * 1024; // 3145728
const EBOOK_EARLIEST_CREATION_DATE = new DateTimeImmutable('2014-01-01');

const ARTWORK_THUMBNAIL_HEIGHT = 350;
const ARTWORK_THUMBNAIL_WIDTH = 350;
const ARTWORK_PER_PAGE = 20;
const ARTWORK_MAX_STRING_LENGTH = 250;
const ARTWORK_MAX_TAGS = 15;
const ARTWORK_IMAGE_MINIMUM_WIDTH = 300;
const ARTWORK_IMAGE_MINIMUM_HEIGHT = 300;

const CAPTCHA_IMAGE_HEIGHT = 72;
const CAPTCHA_IMAGE_WIDTH = 230;

// These are defined for convenience, so that getting HTTP input isn't so wordy
const GET = Enums\HttpVariableSource::Get;
const POST = Enums\HttpVariableSource::Post;
const SESSION = Enums\HttpVariableSource::Session;
const COOKIE = Enums\HttpVariableSource::Cookie;

define('NO_REPLY_EMAIL_ADDRESS', get_cfg_var('se.secrets.email.no_reply_address'));
define('ADMIN_EMAIL_ADDRESS', get_cfg_var('se.secrets.email.admin_address'));
define('EDITOR_IN_CHIEF_EMAIL_ADDRESS', get_cfg_var('se.secrets.email.editor_in_chief_address'));

const EDITOR_IN_CHIEF_NAME = 'Alex Cabal';

define('EMAIL_SMTP_USERNAME', get_cfg_var('se.secrets.postmark.username'));
const EMAIL_SMTP_HOST = 'smtp.postmarkapp.com';
const EMAIL_POSTMARK_STREAM_BROADCAST = 'the-standard-ebooks-newsletter';

const AVERAGE_READING_WORDS_PER_MINUTE = 275;

const FA_FEE_PERCENT = 0.87;

const GITHUB_IGNORED_REPOS =		['tools', 'manual', 'web']; // If we get GitHub push requests featuring these repos, silently ignore instead of returning an error.

const GITHUB_WEBHOOK_LOG_FILE_PATH =	'/var/log/local/webhooks-github.log'; // Must be writable by `www-data` Unix user.
const POSTMARK_WEBHOOK_LOG_FILE_PATH =	'/var/log/local/webhooks-postmark.log'; // Must be writable by `www-data` Unix user.
const ZOHO_WEBHOOK_LOG_FILE_PATH =	'/var/log/local/webhooks-zoho.log'; // Must be writable by `www-data` Unix user.
const DONATIONS_LOG_FILE_PATH =		'/var/log/local/donations.log'; // Must be writable by `www-data` Unix user.
const ARTWORK_UPLOADS_LOG_FILE_PATH =	'/var/log/local/artwork-uploads.log'; // Must be writable by `www-data` Unix user.

define('PD_YEAR', intval($nowPd->format('Y')) - 96);
define('PD_STRING', 'January 1, ' . (PD_YEAR + 1));

define('DONATION_HOLIDAY_ALERT_ON', $now > new DateTimeImmutable('November 15, ' . $now->format('Y')) || $now < new DateTimeImmutable('January 7, ' . $now->add(new DateInterval('P1Y'))->format('Y')));
define('DONATION_ALERT_ON', DONATION_HOLIDAY_ALERT_ON || rand(1, 4) == 2);

// Controls the progress bar donation dialog
const DONATION_DRIVE_ON = true;
const DONATION_DRIVE_START = 'May 20, 2024 00:00:00 America/New_York';
const DONATION_DRIVE_END = 'June 3, 2024 23:59:00 America/New_York';

// Controls the countdown donation dialog
const DONATION_DRIVE_COUNTER_ON = false;
const DONATION_DRIVE_COUNTER_START = 'May 2, 2022 00:00:00 America/New_York';
const DONATION_DRIVE_COUNTER_END = 'May 8, 2022 23:59:00 America/New_York';
