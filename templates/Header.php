<?
use Safe\DateTimeImmutable;
use function Safe\filemtime;

/**
 * @var ?string $title
 * @var ?string $highlight
 * @var ?string $description
 * @var ?string $feedUrl
 * @var ?string $feedTitle
 * @var ?string $downloadUrl
 * @var ?string $canonicalUrl
 * @var ?string $coverUrl
 */

$title ??= null;
$highlight ??= null;
$description ??= null;
$feedUrl ??= null;
$feedTitle ??= null;
$downloadUrl ??= null;
$canonicalUrl ??= null;
$coverUrl ??= null;
$css ??= [];
$isManual ??= false;
$isXslt ??= false;
$isErrorPage ??= false;
$ogType ??= 'website';

$colorScheme = Enums\ColorSchemeType::tryFrom(HttpInput::Str(COOKIE, 'color-scheme') ?? Enums\ColorSchemeType::Auto->value);
$showPublicDomainDayBanner = PD_NOW > new DateTimeImmutable('January 1, 9:00 AM', SITE_TZ) && PD_NOW < new DateTimeImmutable('January 14', LATEST_CONTINENTAL_US_TZ) && !(HttpInput::Bool(COOKIE, 'hide-public-domain-day-banner') ?? false);

// As of Sep. 2022, all versions of Safari have a bug where if the page is served as XHTML, then `<picture>` elements download all `<source>`s instead of the first supported match.
// So, we try to detect Safari here, and don't use multiple `<source>` if we find Safari.
// See <https://bugs.webkit.org/show_bug.cgi?id=245411>.
/** @var string $httpUserAgent */
$httpUserAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$isSafari = stripos($httpUserAgent, 'safari') !== false;

if(!$isErrorPage){
	/** @var string $url */
	$url = $_SERVER['ORIG_PATH_INFO'] ?? $_SERVER['SCRIPT_URI'] ?? '';
	$pageUrl = SITE_URL . str_replace(SITE_URL, '', ($url));
}

if(!$isXslt){
	if(!$isSafari){
		header('content-type: application/xhtml+xml; charset=utf-8');
		print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n");
	}
	print("<!DOCTYPE html>\n");
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
<head prefix="twitter: https://twitter.com/ schema: http://schema.org/"><? /* The `og` RDFa prefix is part of the RDFa spec */ ?>
	<meta charset="utf-8"/>
	<title><? if($title !== null){ ?><?= Formatter::EscapeHtml($title) ?> - <? } ?>Standard Ebooks: Free and liberated ebooks, carefully produced for the true book lover</title>
	<? if($description !== null){ ?>
		<meta content="<?= Formatter::EscapeHtml($description) ?>" name="description"/>
	<? } ?>
	<meta content="width=device-width, initial-scale=1" name="viewport"/>
	<link rel="preload" as="font" href="/fonts/crimson-pro.woff2" type="font/woff2" crossorigin="anonymous"/> <? /* Fonts require the crossorigin attribute */ ?>
	<link rel="preload" as="font" href="/fonts/league-spartan-bold.woff2" type="font/woff2" crossorigin="anonymous"/>
	<link rel="preload" as="font" href="/fonts/fork-awesome-subset.woff2" type="font/woff2" crossorigin="anonymous"/>
	<link rel="preload" as="font" href="/fonts/crimson-pro-italic.woff2" type="font/woff2" crossorigin="anonymous"/> <? /* Don't preload bold/bold-italic as those are used far less frequently */ ?>
	<? if(Template::IsEreaderBrowser()){ ?>
		<link rel="preload" as="font" href="/fonts/league-spartan-bold.ttf" type="font/ttf" crossorigin="anonymous"/>
		<link href="/css/ereader.css?version=<?= filemtime(WEB_ROOT . '/css/ereader.css') ?>" media="screen" rel="stylesheet" type="text/css"/>
	<? }else{ ?>
		<link href="/css/core.css?version=<?= filemtime(WEB_ROOT . '/css/core.css') ?>" media="screen" rel="stylesheet" type="text/css"/>
		<? if($colorScheme == Enums\ColorSchemeType::Auto || $colorScheme == Enums\ColorSchemeType::Dark){ ?>
			<link href="/css/dark.css?version=<?= filemtime(WEB_ROOT . '/css/dark.css') ?>" media="screen<? if($colorScheme == Enums\ColorSchemeType::Auto){ ?> and (prefers-color-scheme: dark)<? } ?>" rel="stylesheet" type="text/css"/>
		<? } ?>
	<? } ?>
	<? if($isManual){ ?>
		<link href="/css/manual.css?version=<?= filemtime(WEB_ROOT . '/css/manual.css') ?>" media="screen" rel="stylesheet" type="text/css"/>
		<? if($colorScheme == Enums\ColorSchemeType::Auto || $colorScheme == Enums\ColorSchemeType::Dark){ ?>
			<link href="/css/manual-dark.css?version=<?= filemtime(WEB_ROOT . '/css/manual-dark.css') ?>" media="screen<? if($colorScheme == Enums\ColorSchemeType::Auto){ ?> and (prefers-color-scheme: dark)<? } ?>" rel="stylesheet" type="text/css"/>
		<? } ?>
	<? } ?>
	<? foreach($css as $url){ ?>
		<link href="<?= Formatter::EscapeHtml($url) ?>?version=<?= filemtime(WEB_ROOT . $url) ?>" media="screen" rel="stylesheet" type="text/css"/>
	<? } ?>
	<? if($canonicalUrl !== null){ ?>
		<link rel="canonical" href="<?= Formatter::EscapeHtml($canonicalUrl) ?>" />
	<? } ?>
	<link href="/apple-touch-icon-120x120.png" rel="apple-touch-icon" sizes="120x120"/>
	<link href="/apple-touch-icon-152x152.png" rel="apple-touch-icon" sizes="152x152"/>
	<link href="/apple-touch-icon.png" rel="apple-touch-icon" sizes="180x180"/>
	<link href="/favicon-32x32.png" rel="icon" sizes="32x32" type="image/png"/>
	<link href="/favicon-16x16.png" rel="icon" sizes="16x16" type="image/png"/>
	<link href="/manifest.json" rel="manifest"/>
	<? if($feedUrl !== null){ ?>
		<link rel="alternate" type="application/atom+xml" title="<?= Formatter::EscapeHtml($feedTitle) ?>" href="<?= SITE_URL ?>/feeds/atom<?= Formatter::EscapeHtml($feedUrl) ?>"/>
		<link rel="alternate" type="application/atom+xml;profile=opds-catalog;kind=acquisition" title="<?= Formatter::EscapeHtml($feedTitle) ?>" href="<?= SITE_URL ?>/feeds/opds<?= Formatter::EscapeHtml($feedUrl) ?>"/>
		<link rel="alternate" type="application/rss+xml" title="<?= Formatter::EscapeHtml($feedTitle) ?>" href="<?= SITE_URL ?>/feeds/rss<?= Formatter::EscapeHtml($feedUrl) ?>"/>
	<? } ?>
	<link rel="alternate" type="application/atom+xml" title="Standard Ebooks - New Releases" href="<?= SITE_URL ?>/feeds/atom/new-releases"/>
	<link rel="alternate" type="application/rss+xml" title="Standard Ebooks - New Releases" href="<?= SITE_URL ?>/feeds/rss/new-releases"/>
	<link rel="alternate" type="application/atom+xml;profile=opds-catalog;kind=acquisition" title="Standard Ebooks - New Releases" href="<?= SITE_URL ?>/feeds/opds/new-releases"/>
	<link rel="alternate" type="application/atom+xml" title="Standard Ebooks - Blog" href="<?= SITE_URL ?>/feeds/atom/blog"/>
	<link rel="alternate" type="application/rss+xml" title="Standard Ebooks - Blog" href="<?= SITE_URL ?>/feeds/rss/blog"/>
	<link rel="search" href="<?= SITE_URL ?>/opensearch" type="application/opensearchdescription+xml" title="Standard Ebooks"/><? // Firefox will show this as `Search with Standard Ebooks`. Can't include `charset` in the MIME type because Chrome will refuse it. ?>
	<? if(!$isErrorPage){ ?>
		<meta content="#394451" name="theme-color"/>
		<meta content="<? if($title !== null){ ?><?= Formatter::EscapeHtml($title) ?><? }else{ ?>Standard Ebooks<? } ?>" property="og:title"/>
		<meta content="<?= $ogType ?>" property="og:type"/>
		<meta content="<?= $pageUrl ?>" property="og:url"/>
		<meta content="<?= SITE_URL . ($coverUrl ?? '/images/logo.png') ?>" property="og:image"/>
		<meta content="summary_large_image" name="twitter:card"/>
		<meta content="@standardebooks" name="twitter:site"/>
		<meta content="@standardebooks" name="twitter:creator"/>
		<meta content="@standardebooks@mastodon.social" name="fediverse:creator"/>
	<? } ?>
	<? if($downloadUrl !== null){ ?>
		<meta http-equiv="refresh" content="0; url=<?= Formatter::EscapeHtml($downloadUrl) ?>" />
	<? } ?>
</head>
<body>
	<header>
		<? if($showPublicDomainDayBanner){ ?>
			<div class="public-domain-day-banner">
				<div id="confettis">
					<div class="confetti"></div>
					<div class="confetti"></div>
					<div class="confetti"></div>
					<div class="confetti"></div>
					<div class="confetti"></div>
					<div class="confetti"></div>
					<div class="confetti"></div>
					<div class="confetti"></div>
					<div class="confetti"></div>
				</div>
				<a href="/blog/public-domain-day-<?= PD_NOW->format('Y') ?>"><strong>Happy Public Domain Day <?= PD_NOW->format('Y') ?>!</strong> See what new books are free to read this January 1.</a>
				<form action="/settings" method="<?= Enums\HttpMethod::Post->value ?>">
					<input type="hidden" name="_method" value="<?= Enums\HttpMethod::Patch->value ?>" />
					<input type="hidden" name="hide-public-domain-day-banner" value="true" />
					<button class="close" title="Hide this banner">Hide this banner</button>
				</form>
			</div>
		<? } ?>
		<a href="/">Standard Ebooks</a>
		<? /* This link is hidden to regular users, and also disallowed by `robots.txt`. If a rude bot crawls this URL, `fail2ban` bans the IP for 24 hours. See `./config/fail2ban/filter.d/se.conf`. */ ?>
		<a href="/honeypot" hidden="hidden" class="honeypot" rel="nofollow">Following this link will ban your IP for 24 hours</a>
		<nav>
			<ul>
				<li>
					<a<? if($highlight == 'ebooks'){ ?> aria-current="page"<? } ?> href="/ebooks">Ebooks</a>
				</li>
				<li>
					<a<? if($highlight == 'about'){ ?> aria-current="page"<? } ?> href="/about">About</a>
				</li>
				<li>
					<a<? if($highlight == 'newsletter'){ ?> aria-current="page"<? } ?> href="/newsletter">Newsletter</a>
				</li>
				<li>
					<a<? if($highlight == 'contribute'){ ?> aria-current="page"<? } ?> href="/contribute">Get Involved</a>
				</li>
				<li>
					<a<? if($highlight == 'donate'){ ?> aria-current="page"<? } ?> href="/donate">Donate</a>
				</li>
			</ul>
		</nav>
	</header>
