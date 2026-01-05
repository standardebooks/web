<?
header('Content-Type: application/atom+xml');
print('<?xml version="1.0" encoding="utf-8"?>');
?>
<feed xmlns="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/">
	<id><?= SITE_URL ?>/feeds/atom/blog</id>
	<link href="<?= SITE_URL ?>/feeds/atom/blog" rel="self" type="application/atom+xml"/>
	<title>Standard Ebooks - Blog</title>
	<subtitle>The Standard Ebooks blog.</subtitle>
	<icon><?= SITE_URL ?>/images/logo.png</icon>
	<updated>2025-11-19T22:31:36Z</updated>
	<author>
		<name>Standard Ebooks</name>
		<uri><?= SITE_URL ?></uri>
	</author>
	<? foreach(BlogPost::GetAllByIsPublished() as $blogPost){ ?>
		<entry>
			<id><?= SITE_URL ?><?= $blogPost->Url ?></id>
			<link href="<?= SITE_URL ?><?= $blogPost->Url ?>" />
			<title type="html"><?= Formatter::EscapeXml($blogPost->Title) ?></title>
			<author>
				<name><?= Formatter::EscapeXml($blogPost->User->Name) ?></name>
			</author>
			<published><?= $blogPost->Published->format(Enums\DateTimeFormat::Iso->value) ?></published>
			<updated><?= $blogPost->Updated->format(Enums\DateTimeFormat::Iso->value) ?></updated>
			<rights>https://creativecommons.org/publicdomain/zero/1.0/</rights>
			<? if(isset($blogPost->Excerpt)){ ?>
				<content type="text"><?= $blogPost->Excerpt ?></content>
			<? } ?>
		</entry>
	<? } ?>
</feed>
