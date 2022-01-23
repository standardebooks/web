<?

if(!isset($title)){
	$title = '';
}

if(!isset($highlight)){
	$highlight = '';
}

if(!isset($description)){
	$description = '';
}

if(!isset($manual)){
	$manual = false;
}

$colorScheme = $_COOKIE['color-scheme'] ?? 'auto';

header('content-type: application/xhtml+xml');
print('<?xml version="1.0" encoding="utf-8"?>');
print("\n");
?><!DOCTYPE html>
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
	<link href="/css/core.css?version=<?= filemtime(WEB_ROOT . '/css/core.css') ?>" media="screen" rel="stylesheet" type="text/css"/>
	<? if($colorScheme == 'auto' || $colorScheme == 'dark'){ ?>
	<link href="/css/dark.css?version=<?= filemtime(WEB_ROOT . '/css/dark.css') ?>" media="screen<? if($colorScheme == 'auto'){ ?> and (prefers-color-scheme: dark)<? } ?>" rel="stylesheet" type="text/css"/>
	<? } ?>
	<? if($manual){ ?>
	<link href="/css/manual.css?version=<?= filemtime(WEB_ROOT . '/css/manual.css') ?>" media="screen" rel="stylesheet" type="text/css"/>
	<? if($colorScheme == 'auto' || $colorScheme == 'dark'){ ?>
	<link href="/css/manual-dark.css?version=<?= filemtime(WEB_ROOT . '/css/manual-dark.css') ?>" media="screen<? if($colorScheme == 'auto'){ ?> and (prefers-color-scheme: dark)<? } ?>" rel="stylesheet" type="text/css"/>
	<? } ?>
	<? } ?>
	<link href="/apple-touch-icon-120x120.png" rel="apple-touch-icon" sizes="120x120"/>
	<link href="/apple-touch-icon-152x152.png" rel="apple-touch-icon" sizes="152x152"/>
	<link href="/apple-touch-icon.png" rel="apple-touch-icon" sizes="180x180"/>
	<link href="/favicon-32x32.png" rel="icon" sizes="32x32" type="image/png"/>
	<link href="/favicon-16x16.png" rel="icon" sizes="16x16" type="image/png"/>
	<link href="/manifest.json" rel="manifest"/>
	<link rel="alternate" type="application/rss+xml" title="Standard Ebooks - New Releases" href="https://standardebooks.org/rss/new-releases"/>
	<meta content="#394451" name="theme-color"/>
	<meta content="<? if($title != ''){ ?><?= Formatter::ToPlainText($title) ?><? }else{ ?>Standard Ebooks<? } ?>" property="og:title"/>
	<meta content="<?= $ogType ?? 'website' ?>" property="og:type"/>
	<meta content="<?= SITE_URL . str_replace(SITE_URL, '', ($_SERVER['ORIG_PATH_INFO'] ?? $_SERVER['SCRIPT_URI'] ?? '')) ?>" property="og:url"/>
	<meta content="<?= SITE_URL . ($coverUrl ?? '/images/devices@2x.png') ?>" property="og:image"/>
	<meta content="summary_large_image" name="twitter:card"/>
	<meta content="@standardebooks" name="twitter:site"/>
	<meta content="@standardebooks" name="twitter:creator"/>
</head>
<body>
	<header>
		<a href="/">Standard Ebooks</a>
		<nav>
			<ul>
				<li>
					<a<? if($highlight == 'ebooks'){ ?> class="highlighted"<? } ?> href="/ebooks">Ebooks</a>
				</li>
				<li>
					<a<? if($highlight == 'about'){ ?> class="highlighted"<? } ?> href="/about">About</a>
				</li>
				<li>
					<a<? if($highlight == 'newsletter'){ ?> class="highlighted"<? } ?> href="/newsletter">Newsletter</a>
				</li>
				<li>
					<a<? if($highlight == 'contribute'){ ?> class="highlighted"<? } ?> href="/contribute">Get Involved</a>
				</li>
				<li>
					<a<? if($highlight == 'donate'){ ?> class="highlighted"<? } ?> href="/donate">Donate</a>
				</li>
			</ul>
		</nav>
	</header>
