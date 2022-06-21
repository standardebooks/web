<?
// Note that the XSL stylesheet gets stripped during `se clean` when we generate the feed.
// `se clean` will also start adding empty namespaces everywhere if we include the stylesheet declaration first.
// We have to add it programmatically when saving the feed file.
print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n");
?>
<feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/">
	<id><?= Formatter::ToPlainXmlText($id) ?></id>
	<link href="<?= SITE_URL . htmlspecialchars($url, ENT_QUOTES|ENT_XML1, 'utf-8') ?>" rel="self" type="application/atom+xml;profile=opds-catalog;kind=navigation"/>
	<link href="<?= SITE_URL ?>/opds" rel="start" type="application/atom+xml;profile=opds-catalog;kind=navigation"/>
	<link href="<?= SITE_URL ?>/opds/all" rel="http://opds-spec.org/crawlable" type="application/atom+xml;profile=opds-catalog;kind=acquisition"/>
	<link href="<?= SITE_URL ?>/ebooks/opensearch" rel="search" type="application/opensearchdescription+xml"/>
	<? if($parentUrl !== null){ ?><link href="<?= SITE_URL ?><?= Formatter::ToPlainXmlText($parentUrl) ?>" rel="up" type="application/atom+xml;profile=opds-catalog;kind=navigation"/><? } ?>
	<title><?= Formatter::ToPlainXmlText($title) ?></title>
	<subtitle>Free and liberated ebooks, carefully produced for the true book lover.</subtitle>
	<icon><?= SITE_URL ?>/images/logo.png</icon>
	<updated><?= $updatedTimestamp->format('Y-m-d\TH:i:s\Z') ?></updated>
	<author>
		<name>Standard Ebooks</name>
		<uri><?= SITE_URL ?></uri>
	</author>
	<? foreach($entries as $entry){ ?>
		<entry>
			<title><?= Formatter::ToPlainXmlText($entry->Title) ?></title>
			<link href="<?= SITE_URL . Formatter::ToPlainXmlText($entry->Url) ?>" rel="<?= Formatter::ToPlainXmlText($entry->Rel) ?>" type="application/atom+xml;profile=opds-catalog;kind=<?= $entry->Type ?>"/>
			<updated><? if($entry->Updated !== null){ ?><?= $entry->Updated->format('Y-m-d\TH:i:s\Z') ?><? } ?></updated>
			<id><?= Formatter::ToPlainXmlText($entry->Id) ?></id>
			<content type="text"><?= Formatter::ToPlainXmlText($entry->Description) ?></content>
		</entry>
	<? } ?>
</feed>
