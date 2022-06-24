<?
$subtitle = $subtitle ?? null;

// Note that the XSL stylesheet gets stripped during `se clean` when we generate the feed.
// `se clean` will also start adding empty namespaces everywhere if we include the stylesheet declaration first.
// We have to add it programmatically when saving the feed file.
print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n");
?>
<feed xmlns="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/">
	<id><?= SITE_URL . Formatter::ToPlainXmlText($id) ?></id>
	<link href="<?= SITE_URL . Formatter::ToPlainXmlText($url) ?>" rel="self" type="application/atom+xml"/>
	<title><?= Formatter::ToPlainXmlText($title) ?></title>
	<? if($subtitle !== null){ ?><subtitle><?= Formatter::ToPlainXmlText($subtitle) ?></subtitle><? } ?>
	<icon><?= SITE_URL ?>/images/logo.png</icon>
	<updated><?= $updatedTimestamp->format('Y-m-d\TH:i:s\Z') ?></updated>
	<author>
		<name>Standard Ebooks</name>
		<uri><?= SITE_URL ?></uri>
	</author>
	<link href="<?= SITE_URL ?>/ebooks/opensearch" rel="search" type="application/opensearchdescription+xml" />
	<? foreach($entries as $entry){ ?>
		<?= Template::AtomFeedEntry(['entry' => $entry]) ?>
	<? } ?>
</feed>
