<?

$title = $title ?? '';
$highlight = $highlight ?? '';
$description = $description ?? '';
$manual = $manual ?? false;
$artwork = $artwork ?? false;
$colorScheme = $_COOKIE['color-scheme'] ?? 'auto';
$isXslt = $isXslt ?? false;
$feedUrl = $feedUrl ?? null;
$feedTitle = $feedTitle ?? '';
$is404 = $is404 ?? false;

// As of Sep 2022, all versions of Safari have a bug where if the page is served as XHTML,
// then <picture> elements download all <source>s instead of the first supported match.
// So, we try to detect Safari here, and don't use multiple <source> if we find Safari.
// See https://bugs.webkit.org/show_bug.cgi?id=245411
$isSafari = stripos($_SERVER['HTTP_USER_AGENT'] ?? '', 'safari') !== false;

if(!$isXslt){
	if(!$isSafari){
		header('content-type: application/xhtml+xml; charset=utf-8');
		print('<?xml version="1.0" encoding="utf-8"?>');
		print("\n");
	}
	print("<!DOCTYPE html>\n");
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
<head prefix="twitter: https://twitter.com/ schema: http://schema.org/"><? /* The `og` RDFa prefix is part of the RDFa spec */ ?>
	<meta charset="utf-8"/>
	<title><? if($title != ''){ ?><?= Formatter::ToPlainText($title) ?> - <? } ?>Standard Ebooks: Free and liberated ebooks, carefully produced for the true book lover.</title>
	<? if($description != ''){ ?><meta content="<?= Formatter::ToPlainText($description) ?>" name="description"/><? } ?>
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
	<? if($colorScheme == 'auto' || $colorScheme == 'dark'){ ?>
	<link href="/css/dark.css?version=<?= filemtime(WEB_ROOT . '/css/dark.css') ?>" media="screen<? if($colorScheme == 'auto'){ ?> and (prefers-color-scheme: dark)<? } ?>" rel="stylesheet" type="text/css"/>
	<? } ?>
	<? } ?>
	<? if($manual){ ?>
	<link href="/css/manual.css?version=<?= filemtime(WEB_ROOT . '/css/manual.css') ?>" media="screen" rel="stylesheet" type="text/css"/>
	<? if($colorScheme == 'auto' || $colorScheme == 'dark'){ ?>
	<link href="/css/manual-dark.css?version=<?= filemtime(WEB_ROOT . '/css/manual-dark.css') ?>" media="screen<? if($colorScheme == 'auto'){ ?> and (prefers-color-scheme: dark)<? } ?>" rel="stylesheet" type="text/css"/>
	<? } ?>
	<? } ?>
	<? if($artwork){ ?>
	<link href="/css/artwork.css?version=<?= filemtime(WEB_ROOT . '/css/artwork.css') ?>" media="screen" rel="stylesheet" type="text/css"/>
	<? } ?>
	<link href="/apple-touch-icon-120x120.png" rel="apple-touch-icon" sizes="120x120"/>
	<link href="/apple-touch-icon-152x152.png" rel="apple-touch-icon" sizes="152x152"/>
	<link href="/apple-touch-icon.png" rel="apple-touch-icon" sizes="180x180"/>
	<link href="/favicon-32x32.png" rel="icon" sizes="32x32" type="image/png"/>
	<link href="/favicon-16x16.png" rel="icon" sizes="16x16" type="image/png"/>
	<link href="/manifest.json" rel="manifest"/>
	<? if($feedUrl === null){ ?>
	<link rel="alternate" type="application/atom+xml" title="Standard Ebooks - New Releases" href="https://standardebooks.org/feeds/atom/new-releases"/>
	<link rel="alternate" type="application/atom+xml;profile=opds-catalog;kind=acquisition" title="Standard Ebooks - New Releases" href="https://standardebooks.org/feeds/opds/new-releases"/>
	<link rel="alternate" type="application/rss+xml" title="Standard Ebooks - New Releases" href="https://standardebooks.org/feeds/rss/new-releases"/>
	<? }else{ ?>
	<link rel="alternate" type="application/atom+xml" title="<?= Formatter::ToPlainText($feedTitle) ?>" href="/feeds/atom<?= $feedUrl ?>"/>
	<link rel="alternate" type="application/atom+xml;profile=opds-catalog;kind=acquisition" title="<?= Formatter::ToPlainText($feedTitle) ?>" href="/feeds/opds<?= $feedUrl ?>"/>
	<link rel="alternate" type="application/rss+xml" title="<?= Formatter::ToPlainText($feedTitle) ?>" href="/feeds/rss<?= $feedUrl ?>"/>
	<? } ?>
	<link rel="search" href="/ebooks" type="application/xhtml+xml; charset=utf-8"/>
	<link rel="search" href="/ebooks/opensearch" type="application/opensearchdescription+xml; charset=utf-8"/>
	<? if(!$is404){ ?>
	<meta content="#394451" name="theme-color"/>
	<meta content="<? if($title != ''){ ?><?= Formatter::ToPlainText($title) ?><? }else{ ?>Standard Ebooks<? } ?>" property="og:title"/>
	<meta content="<?= $ogType ?? 'website' ?>" property="og:type"/>
	<meta content="<?= SITE_URL . str_replace(SITE_URL, '', ($_SERVER['ORIG_PATH_INFO'] ?? $_SERVER['SCRIPT_URI'] ?? '')) ?>" property="og:url"/>
	<meta content="<?= SITE_URL . ($coverUrl ?? '/images/logo.png') ?>" property="og:image"/>
	<meta content="summary_large_image" name="twitter:card"/>
	<meta content="@standardebooks" name="twitter:site"/>
	<meta content="@standardebooks" name="twitter:creator"/>
	<? } ?>
</head>
<body>
	<header>
		<a href="/">Standard Ebooks</a>
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
