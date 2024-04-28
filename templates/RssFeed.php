<?
// Note that the XSL stylesheet gets stripped during `se clean` when we generate the feed.
// `se clean` will also start adding empty namespaces everywhere if we include the stylesheet declaration first.
// We have to add it programmatically when saving the feed file.
print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n");
?><rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/">
	<channel>
		<title><?= Formatter::EscapeXml($title) ?></title>
		<link><?= SITE_URL ?></link>
		<description><?= Formatter::EscapeXml($description) ?></description>
		<language>en-US</language>
		<copyright>https://creativecommons.org/publicdomain/zero/1.0/</copyright>
		<lastBuildDate><?= $updated ?></lastBuildDate>
		<docs>http://blogs.law.harvard.edu/tech/rss</docs>
		<atom:link href="<?= SITE_URL . Formatter::EscapeXml($url) ?>" rel="self" type="application/rss+xml"/>
		<atom:link href="<?= SITE_URL ?>/ebooks/opensearch" rel="search" type="application/opensearchdescription+xml" />
		<image>
			<url><?= SITE_URL ?>/images/logo-rss.png</url>
			<title><?= Formatter::EscapeXml($title) ?></title> <? /* must be identical to channel title */ ?>
			<description>The Standard Ebooks logo</description>
			<link><?= SITE_URL ?></link>
			<height>144</height>
			<width>144</width>
		</image>
		<? foreach($entries as $entry){ ?>
			<?= Template::RssEntry(['entry' => $entry]) ?>
		<? } ?>
	</channel>
</rss>
