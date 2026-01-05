<?
/**
 * @var string $title
 * @var string $description
 * @var DateTimeImmutable $updated
 * @var string $url
 * @var array<Ebook> $entries
 */

// The `xslt-polyfill.min.js` script is to support the deprecation of XSLT in major browsers that occurred in 2025-2026.
//
// See:
//
// - <https://developer.chrome.com/docs/web-platform/deprecating-xslt#rss_and_atom_feeds>
//
// - <https://github.com/mfreed7/xslt_polyfill>

// Note that the XSL stylesheet gets stripped during `se clean` when we generate the feed.
// `se clean` will also start adding empty namespaces everywhere if we include the stylesheet declaration first.
// We have to add it programmatically when saving the feed file.
print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n");
?><rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/">
	<script src="<?= SITE_URL ?>/scripts/xslt-polyfill.min.js" xmlns="http://www.w3.org/1999/xhtml"></script>
	<channel>
		<title><?= Formatter::EscapeXml($title) ?></title>
		<link><?= SITE_URL . Formatter::EscapeXml($url) ?></link>
		<description><?= Formatter::EscapeXml($description) ?></description>
		<language>en-US</language>
		<copyright>https://creativecommons.org/publicdomain/zero/1.0/</copyright>
		<lastBuildDate><?= $updated->format(Enums\DateTimeFormat::Rss->value); ?></lastBuildDate>
		<docs>http://blogs.law.harvard.edu/tech/rss</docs>
		<atom:link href="<?= SITE_URL . Formatter::EscapeXml($url) ?>" rel="self" type="application/rss+xml"/>
		<atom:link href="<?= SITE_URL ?>/opensearch" rel="search" type="application/opensearchdescription+xml" title="Standard Ebooks"/>
		<image>
			<url><?= SITE_URL ?>/images/logo-rss.png</url>
			<title><?= Formatter::EscapeXml($title) ?></title> <? /* must be identical to channel title */ ?>
			<description>The Standard Ebooks logo</description>
			<link><?= SITE_URL . Formatter::EscapeXml($url) ?></link>
			<height>144</height>
			<width>144</width>
		</image>
		<? foreach($entries as $entry){ ?>
			<?= Template::RssEntry(entry: $entry) ?>
		<? } ?>
	</channel>
</rss>
