<entry>
	<id><?= SITE_URL . $entry->Url ?></id>
	<title><?= Formatter::EscapeXml($entry->Title) ?></title>
	<? foreach($entry->Authors as $author){ ?>
		<author>
			<name><?= Formatter::EscapeXml($author->Name) ?></name>
			<uri><?= SITE_URL . Formatter::EscapeXml($entry->AuthorsUrl) ?></uri>
		</author>
	<? } ?>
	<published><?= $entry->Created->format('Y-m-d\TH:i:s\Z') ?></published>
	<updated><?= $entry->Updated->format('Y-m-d\TH:i:s\Z') ?></updated>
	<rights>Public domain in the United States. Users located outside of the United States must check their local laws before using this ebook. Original content released to the public domain via the Creative Commons CC0 1.0 Universal Public Domain Dedication.</rights>
	<summary type="text"><?= Formatter::EscapeXml($entry->Description) ?></summary>
	<content type="html"><?= Formatter::EscapeXml($entry->LongDescription) ?></content>
	<? foreach($entry->LocSubjects as $subject){ ?>
	<category scheme="http://purl.org/dc/terms/LCSH" term="<?= Formatter::EscapeXml($subject->Name) ?>"/>
	<? } ?>
	<? foreach($entry->Tags as $subject){ ?>
	<category scheme="https://standardebooks.org/vocab/subjects" term="<?= Formatter::EscapeXml($subject->Name) ?>"/>
	<? } ?>
	<media:thumbnail url="<?= SITE_URL . $entry->Url ?>/downloads/cover-thumbnail.jpg" height="525" width="350"/>
	<link href="<?= SITE_URL . $entry->Url ?>" rel="alternate" title="This ebook’s page at Standard Ebooks" type="application/xhtml+xml"/>
	<? if(file_exists(WEB_ROOT . $entry->EpubUrl)){ ?><link href="<?= SITE_URL . $entry->EpubUrl ?>" length="<?= filesize(WEB_ROOT . $entry->EpubUrl) ?>" rel="enclosure" title="Recommended compatible epub" type="application/epub+zip" /><? } ?>
	<? if(file_exists(WEB_ROOT . $entry->AdvancedEpubUrl)){ ?><link href="<?= SITE_URL . $entry->AdvancedEpubUrl ?>" length="<?= filesize(WEB_ROOT . $entry->AdvancedEpubUrl) ?>" rel="enclosure" title="Advanced epub" type="application/epub+zip" /><? } ?>
	<? if(file_exists(WEB_ROOT . $entry->KepubUrl)){ ?><link href="<?= SITE_URL . $entry->KepubUrl ?>" length="<?= filesize(WEB_ROOT . $entry->KepubUrl) ?>" rel="enclosure" title="Kobo Kepub epub" type="application/kepub+zip" /><? } ?>
	<? if(file_exists(WEB_ROOT . $entry->Azw3Url)){ ?><link href="<?= SITE_URL . $entry->Azw3Url ?>" length="<?= filesize(WEB_ROOT . $entry->Azw3Url) ?>" rel="enclosure" title="Amazon Kindle azw3" type="application/x-mobipocket-ebook" /><? } ?>
	<? if(file_exists(WEB_ROOT . $entry->TextSinglePageUrl)){ ?><link href="<?= SITE_URL . $entry->TextSinglePageUrl ?>" length="<?= filesize(WEB_ROOT . $entry->TextSinglePageUrl) ?>" rel="enclosure" title="XHTML" type="application/xhtml+xml" /><? } ?>
</entry>
