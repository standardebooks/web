<?
// Auto-included by Composer in `composer.json` to satisfy PHPStan.
use Safe\DateTimeImmutable;
use function Safe\define;

const NOW = new DateTimeImmutable();
const LATEST_CONTINENTAL_US_TZ = new DateTimeZone('America/Juneau');
const PD_NOW = new DateTimeImmutable('now', LATEST_CONTINENTAL_US_TZ); // This timestamp should be used for Public Domain Day calculations.

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

const EBOOKS_IDENTIFIER_PREFIX =	'url:https://standardebooks.org/ebooks/';

const DATABASE_DEFAULT_DATABASE = 	'se';
const DATABASE_DEFAULT_HOST = 		'localhost';

const EBOOKS_PER_PAGE = 12;
const EBOOKS_MAX_STRING_LENGTH = 250;
const EBOOKS_MAX_LONG_STRING_LENGTH = 500;
const EBOOK_SINGLE_PAGE_SIZE_WARNING = 3 * 1024 * 1024; // 3145728 bytes.

const ARTWORK_THUMBNAIL_HEIGHT = 350;
const ARTWORK_THUMBNAIL_WIDTH = 350;
const ARTWORK_PER_PAGE = 20;
const ARTWORK_MAX_STRING_LENGTH = 250;
const ARTWORK_MAX_TAGS = 15;
const ARTWORK_IMAGE_MINIMUM_WIDTH = 300;
const ARTWORK_IMAGE_MINIMUM_HEIGHT = 300;

const CAPTCHA_IMAGE_HEIGHT = 72;
const CAPTCHA_IMAGE_WIDTH = 230;

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

define('PD_YEAR', intval(PD_NOW->format('Y')) - 96);
define('PD_STRING', 'January 1, ' . (PD_YEAR + 1));

// Controls the progress bar donation dialog.
const DONATION_DRIVES_ENABLED = true; // **`TRUE`** to enable automatic donation drives; **`FALSE`** to disable all donation drives.
const DONATION_DRIVE_DATES = [
				new DonationDrive(
							'Spring drive',
							new DateTimeImmutable('Second Monday of May'),
							new DateTimeImmutable('Second Monday of May +2 weeks'),
							40,
							20
						),
				new DonationDrive(
							'Holiday drive',
							NOW < new DateTimeImmutable('January 7') ? new DateTimeImmutable('November 25 -1 year') : new DateTimeImmutable('November 25'),
							NOW < new DateTimeImmutable('January 7') ? new DateTimeImmutable('January 7') : new DateTimeImmutable('January 7 +1 year'),
							75,
							25
						)
			];

// Controls the countdown donation dialog, basically unused right now.
const DONATION_DRIVE_COUNTER_ENABLED = false;
const DONATION_DRIVE_COUNTER_START = new DateTimeImmutable('May 2, 2022 00:00:00', new DateTimeZone('America/New_York'));
const DONATION_DRIVE_COUNTER_END = new DateTimeImmutable('May 8, 2022 23:59:00', new DateTimeZone('America/New_York'));

const PD_DAY_YEAR = 2025;
const PD_DAY_DRAFT_PATH = '/standardebooks.org/drafts/' . PD_DAY_YEAR;
const PD_DAY_EBOOKS = [
		EBOOKS_IDENTIFIER_PREFIX . 'graham-greene/the-man-within' => ['author' => 'Graham Greene', 'title' => 'The Man Within'],
		EBOOKS_IDENTIFIER_PREFIX . 'c-s-forester/brown-on-resolution' => ['author' => 'C. S. Forester', 'title' => 'Brown on Resolution'],
		EBOOKS_IDENTIFIER_PREFIX . 'dashiell-hammett/red-harvest' => ['author' => 'Dashiell Hammett', 'title' => 'Red Harvest'],
		EBOOKS_IDENTIFIER_PREFIX . 'dashiell-hammett/the-dain-curse' => ['author' => 'Dashiell Hammett', 'title' => 'The Dain Curse'],
		EBOOKS_IDENTIFIER_PREFIX . 'erich-maria-remarque/all-quiet-on-the-western-front/a-w-wheen' => ['author' => 'Erich Maria Remarque', 'title' => 'All Quiet on the Western Front', 'translator' => 'A. W. Wheen'],
		EBOOKS_IDENTIFIER_PREFIX . 'ernest-hemingway/a-farewell-to-arms' => ['author' => 'Ernest Heminway', 'title' => 'A Farewell to Arms'],
		EBOOKS_IDENTIFIER_PREFIX . 'j-b-priestley/the-good-companions' => ['author' => 'J. B. Priestley', 'title' => 'The Good Companions'],
		EBOOKS_IDENTIFIER_PREFIX . 'john-steinbeck/cup-of-gold' => ['author' => 'John Steinbeck', 'title' => 'Cup of Gold'],
		EBOOKS_IDENTIFIER_PREFIX . 'oliver-la-farge/laughing-boy' => ['author' => 'Oliver La Farge', 'title' => 'Laughing Boy'],
		EBOOKS_IDENTIFIER_PREFIX . 'william-faulkner/the-sound-and-the-fury' => ['author' => 'William Faulkner', 'title' => 'The Sound and the Fury'],
		EBOOKS_IDENTIFIER_PREFIX . 'mahatma-gandhi/the-story-of-my-experiments-with-truth/mahadev-desai' => ['author' => 'Mahatma Gandhi', 'title' => 'The Story of My Experiments with Truth'],
		EBOOKS_IDENTIFIER_PREFIX . 'arthur-conan-doyle/the-maracot-deep' => ['author' => 'Arthur Conan Doyle', 'title' => 'The Maracot Deep'],
		EBOOKS_IDENTIFIER_PREFIX . 'sinclair-lewis/dodsworth' => ['author' => 'Sinclair Lewis', 'title' => 'Dodsworth'],
		EBOOKS_IDENTIFIER_PREFIX . 'thomas-wolfe/look-homeward-angel' => ['author' => 'Thomas Wolfe', 'title' => 'Look Homeward, Angel'],
		EBOOKS_IDENTIFIER_PREFIX . 'lloyd-c-douglas/magnificent-obsession' => ['author' => 'Lloyd C. Dougals', 'title' => 'Magnificent Obsession'],
		EBOOKS_IDENTIFIER_PREFIX . 'edith-wharton/hudson-river-bracketed' => ['author' => 'Edith Wharton', 'title' => 'Hudson River Bracketed'],
		EBOOKS_IDENTIFIER_PREFIX . 'calvin-coolidge/the-autobiography-of-calvin-coolidge' => ['author' => 'Calvin Coolidge', 'title' => 'The Autobiography of Calvin Coolidge']
	];
