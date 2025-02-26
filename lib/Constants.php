<?
// Auto-included by Composer in `composer.json` to satisfy PHPStan.
use Safe\DateTimeImmutable;
use function Safe\get_cfg_var;
use function Safe\define;

const NOW = new DateTimeImmutable();
const LATEST_CONTINENTAL_US_TZ = new DateTimeZone('America/Juneau');
/** This timestamp should be used for Public Domain Day calculations. */
const PD_NOW = new DateTimeImmutable('now', LATEST_CONTINENTAL_US_TZ);
define('PD_YEAR', intval(PD_NOW->format('Y')) - 96);
define('PD_STRING', 'January 1, ' . (PD_YEAR + 1));
const SITE_TZ = new  DateTimeZone('America/Chicago');

const SITE_STATUS_LIVE = 		'live';
const SITE_STATUS_DEV =			'dev';

define('SITE_STATUS', get_cfg_var('app.site_status') ?: SITE_STATUS_DEV); // Set in the PHP INI configuration for both CLI and FPM. Have to use `define()` and not `const` so we can use a function.

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

const EBOOKS_IDENTIFIER_ROOT =		'url:https://standardebooks.org';
const EBOOKS_IDENTIFIER_PREFIX =	EBOOKS_IDENTIFIER_ROOT . '/ebooks/';

const DATABASE_DEFAULT_DATABASE = 	'se';
const DATABASE_DEFAULT_HOST = 		'localhost';

const EBOOKS_PER_PAGE = 12;
const EBOOKS_MAX_STRING_LENGTH = 250;
const EBOOKS_MAX_LONG_STRING_LENGTH = 500;
const EBOOK_SINGLE_PAGE_SIZE_WARNING = 3 * 1024 * 1024; // 3145728 bytes.

const EBOOK_SEARCH_WEIGHT_TITLE = 10;
const EBOOK_SEARCH_WEIGHT_AUTHORS = 8;
const EBOOK_SEARCH_WEIGHT_COLLECTIONS = 3;

const ARTWORK_THUMBNAIL_HEIGHT = 350;
const ARTWORK_THUMBNAIL_WIDTH = 350;
const ARTWORK_PER_PAGE = 20;
const ARTWORK_MAX_STRING_LENGTH = 250;
const ARTWORK_MAX_TAGS = 15;
const ARTWORK_IMAGE_MINIMUM_WIDTH = 300;
const ARTWORK_IMAGE_MINIMUM_HEIGHT = 300;

const CAPTCHA_IMAGE_HEIGHT = 72;
const CAPTCHA_IMAGE_WIDTH = 230;

const PATRONS_CIRCLE_MONTHLY_COST = 15;
const PATRONS_CIRCLE_YEARLY_COST = 150;

// These are defined for convenience, so that getting HTTP input isn't so wordy.
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
const EMAIL_LOG_FILE_PATH =		'/var/log/local/standardebooks.org-email.log'; // Must be writable by `www-data` Unix user.


// Controls the progress bar donation dialog.
const DONATION_DRIVES_ENABLED = true; // **`TRUE`** to enable automatic donation drives; **`FALSE`** to disable all donation drives.
const DONATION_DRIVE_DATES = [
				new DonationDrive(
							'Spring drive',
							new DateTimeImmutable('Second Monday of May', SITE_TZ),
							new DateTimeImmutable('Second Monday of May 22:00 +2 weeks', SITE_TZ),
							40,
							20
						),
				new DonationDrive(
							'Holiday drive',
							NOW < new DateTimeImmutable('January 7, 22:00', SITE_TZ) ? new DateTimeImmutable('November 25 -1 year', SITE_TZ) : new DateTimeImmutable('November 25', SITE_TZ),
							NOW < new DateTimeImmutable('January 7, 22:00', SITE_TZ) ? new DateTimeImmutable('January 7 22:00', SITE_TZ) : new DateTimeImmutable('January 7 22:00 +1 year', SITE_TZ),
							75,
							50
						)
			];

// Controls the countdown donation dialog, basically unused right now.
const DONATION_DRIVE_COUNTER_ENABLED = false;
const DONATION_DRIVE_COUNTER_START = new DateTimeImmutable('May 2, 2022 00:00:00', new DateTimeZone('America/New_York'));
const DONATION_DRIVE_COUNTER_END = new DateTimeImmutable('May 8, 2022 23:59:00', new DateTimeZone('America/New_York'));
