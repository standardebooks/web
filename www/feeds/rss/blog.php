<?
header('Content-Type: application/rss+xml');
print('<?xml version="1.0" encoding="utf-8"?>');
?>
<rss xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/" version="2.0">
	<channel>
		<title>Standard Ebooks - Blog</title>
		<link><?= SITE_URL ?>/blog</link>
		<description>The Standard Ebooks Blog</description>
		<language>en-US</language>
		<copyright>https://creativecommons.org/publicdomain/zero/1.0/</copyright>
		<lastBuildDate>Wed, 19 Nov 2025 22:31:36 +0000</lastBuildDate>
		<docs>http://blogs.law.harvard.edu/tech/rss</docs>
		<atom:link href="<?= SITE_URL ?>/feeds/rss/blog" rel="self" type="application/rss+xml"/>
		<image>
			<url><?= SITE_URL ?>/images/logo-rss.png</url>
			<title>Standard Ebooks - Blog</title>
			<description>The Standard Ebooks logo</description>
			<link><?= SITE_URL ?>/blog</link>
			<height>144</height>
			<width>144</width>
		</image>
		<? foreach(BlogPost::GetAllByIsPublished() as $blogPost){ ?>
			<item>
				<title><?= Formatter::EscapeXml(strip_tags($blogPost->Title)) ?></title>
				<guid><?= SITE_URL ?><?= $blogPost->Url ?></guid>
				<link><?= SITE_URL ?><?= $blogPost->Url ?></link>
				<pubDate><?= $blogPost->Published->format(Enums\DateTimeFormat::Rss->value) ?></pubDate>
				<? if(isset($blogPost->Excerpt)){ ?>
					<description><?= $blogPost->Excerpt ?></description>
				<? } ?>
			</item>
		<? } ?>
	</channel>
</rss>

