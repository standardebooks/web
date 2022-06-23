<?
use Safe\DateTime;

// Note that the XSL stylesheet gets stripped during `se clean` when we generate the feed.
// `se clean` will also start adding empty namespaces everywhere if we include the stylesheet declaration first.
// We have to add it programmatically when saving the feed file.
print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n");
?><rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/">
	<channel>
		<title><?= Formatter::ToPlainXmlText($title) ?></title>
		<link><?= SITE_URL ?></link>
		<description><?= Formatter::ToPlainXmlText($description) ?></description>
		<language>en-US</language>
		<copyright>https://creativecommons.org/publicdomain/zero/1.0/</copyright>
		<lastBuildDate><?= $updatedTimestamp ?></lastBuildDate>
		<docs>http://blogs.law.harvard.edu/tech/rss</docs>
		<atom:link href="<?= SITE_URL . Formatter::ToPlainXmlText($url) ?>" rel="self" type="application/rss+xml"/>
		<image>
			<url><?= SITE_URL ?>/images/logo-rss.png</url>
			<title><?= Formatter::ToPlainXmlText($title) ?></title> <? /* must be identical to channel title */ ?>
			<description>The Standard Ebooks logo</description>
			<link><?= SITE_URL ?></link>
			<height>144</height>
			<width>144</width>
		</image>
		<? foreach($entries as $entry){ ?>
		<item>
			<title><?= Formatter::ToPlainXmlText($entry->Title) ?>, by <?= Formatter::ToPlainXmlText(strip_tags($entry->AuthorsHtml)) ?></title>
			<link><?= SITE_URL . Formatter::ToPlainXmlText($entry->Url) ?></link>
			<description><?= Formatter::ToPlainXmlText($entry->Description) ?></description>
			<pubDate><?= $entry->Timestamp->format('r') ?></pubDate>
			<guid><?= Formatter::ToPlainXmlText(preg_replace('/^url:/ius', '', $entry->Identifier)) ?></guid>
			<? foreach($entry->Tags as $tag){ ?>
			<category domain="https://standardebooks.org/vocab/subjects"><?= Formatter::ToPlainXmlText($tag->Name) ?></category>
			<? } ?>
			<media:thumbnail url="<?= SITE_URL . $entry->Url ?>/downloads/cover-thumbnail.jpg" height="525" width="350"/>
			<? if($entry->EpubUrl !== null){ ?>
			<enclosure url="<?= SITE_URL . Formatter::ToPlainXmlText($entry->EpubUrl)  ?>" length="<?= filesize(WEB_ROOT . $entry->EpubUrl) ?>" type="application/epub+zip" />  <? /* Only one <enclosure> is allowed */ ?>
			<? } ?>
		</item>
		<? } ?>
	</channel>
</rss>
