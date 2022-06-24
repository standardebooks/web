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
