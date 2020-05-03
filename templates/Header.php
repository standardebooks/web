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

?><!doctype html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
	<title><? if($title != ''){ ?><?= Formatter::ToPlainText($title) ?> - <? } ?>Standard Ebooks: Free and liberated ebooks, carefully produced for the true book lover.</title>
	<? if($description != ''){ ?><meta content="<?= Formatter::ToPlainText($description) ?>" name="description"><? } ?>
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<link href="/css/core.css" media="screen" rel="stylesheet" type="text/css">
	<? if($manual){ ?>
	<link href="/css/manual.css" media="screen" rel="stylesheet" type="text/css">
	<? } ?>
	<link href="/apple-touch-icon-120x120.png" rel="apple-touch-icon" sizes="120x120">
	<link href="/apple-touch-icon-152x152.png" rel="apple-touch-icon" sizes="152x152">
	<link href="/apple-touch-icon.png" rel="apple-touch-icon" sizes="180x180">
	<link href="/favicon-32x32.png" rel="icon" sizes="32x32" type="image/png">
	<link href="/favicon-16x16.png" rel="icon" sizes="16x16" type="image/png">
	<link href="/manifest.json" rel="manifest">
	<link rel="alternate" type="application/rss+xml" title="Standard Ebooks - New Releases" href="https://standardebooks.org/rss/new-releases">
	<link color="#394451" href="/safari-pinned-tab.svg" rel="mask-icon">
	<meta content="#394451" name="theme-color">
	<meta content="<?= Formatter::ToPlainText($title) ?>" property="og:title">
	<meta content="<?= $ogType ?? 'website' ?>" property="og:type">
	<meta content="<?= SITE_URL . str_replace(SITE_URL, '', ($_SERVER['ORIG_PATH_INFO'] ?? $_SERVER['SCRIPT_URI'] ?? '')) ?>" property="og:url">
	<meta content="<?= SITE_URL . ($coverUrl ?? '/images/logo.svg') ?>" property="og:image">
	<meta content="summary_large_image" name="twitter:card">
	<meta content="@standardebooks" name="twitter:site">
	<meta content="@standardebooks" name="twitter:creator">
	<? if(isset($jsonld)){ ?>
	<script type="application/ld+json">
		<?= $jsonld ?>
	</script>
	<? } ?>
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
					<a<? if($highlight == 'contribute'){ ?> class="highlighted"<? } ?> href="/contribute">Get Involved</a>
				</li>
			</ul>
		</nav>
	</header>
