<item>
	<title><?= Formatter::EscapeXml($entry->Title) ?>, by <?= Formatter::EscapeXml(strip_tags($entry->AuthorsHtml)) ?></title>
	<link><?= SITE_URL . Formatter::EscapeXml($entry->Url) ?></link>
	<description><?= Formatter::EscapeXml($entry->Description) ?></description>
	<pubDate><?= $entry->EbookCreated->format('r') ?></pubDate>
	<guid><?= Formatter::EscapeXml(preg_replace('/^url:/ius', '', $entry->Identifier)) ?></guid>
	<? foreach($entry->Tags as $tag){ ?>
	<category domain="https://standardebooks.org/vocab/subjects"><?= Formatter::EscapeXml($tag->Name) ?></category>
	<? } ?>
	<media:thumbnail url="<?= SITE_URL . $entry->Url ?>/downloads/cover-thumbnail.jpg" height="525" width="350"/>
	<? if($entry->EpubUrl !== null){ ?>
	<enclosure url="<?= SITE_URL . Formatter::EscapeXml($entry->EpubUrl)  ?>" length="<?= filesize(WEB_ROOT . $entry->EpubUrl) ?>" type="application/epub+zip" />  <? /* Only one <enclosure> is allowed */ ?>
	<? } ?>
</item>
