<?

/* Notes:

- *All* OPDS feeds must contain a rel="http://opds-spec.org/crawlable" link pointing to the /opds/all feed

- The <fh:complete/> element is required to note this as a "Complete Acquisition Feeds"; see https://specs.opds.io/opds-1.2#25-complete-acquisition-feeds

*/
print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n");
?>
<feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:schema="http://schema.org/">
	<id><?= $id ?></id>
	<link href="<?= $url ?>" rel="self" type="application/atom+xml;profile=opds-catalog;kind=acquisition"/>
	<link href="/opds" rel="start" type="application/atom+xml;profile=opds-catalog;kind=navigation"/>
	<link href="/opds/all" rel="http://opds-spec.org/crawlable" type="application/atom+xml;profile=opds-catalog;kind=acquisition"/>
	<link href="/ebooks/opensearch" rel="search" type="application/opensearchdescription+xml"/>
	<? if($parentUrl !== null){ ?><link href="<?= $parentUrl ?>" rel="up" type="application/atom+xml;profile=opds-catalog;kind=navigation"/><? } ?>
	<title><?= htmlspecialchars($title, ENT_QUOTES|ENT_XML1, 'utf-8') ?></title>
	<subtitle>Free and liberated ebooks, carefully produced for the true book lover.</subtitle>
	<icon>/images/logo.png</icon>
	<updated><?= $updatedTimestamp ?></updated>
	<author>
		<name>Standard Ebooks</name>
		<uri><?= SITE_URL ?></uri>
	</author>
	<? foreach($entries as $entry){ ?>
		<entry>
			<title><?= htmlspecialchars($entry->Title, ENT_QUOTES|ENT_XML1, 'utf-8') ?></title>
			<link href="<?= $entry->Url ?>" rel="<?= $entry->Rel ?>" type="application/atom+xml;profile=opds-catalog;kind=<?= $entry->Type ?>"/>
			<updated><? if($entry->Updated !== null){ ?><?= $entry->Updated->format('Y-m-d\TH:i:s\Z') ?><? } ?></updated>
			<id><?= htmlspecialchars($entry->Id, ENT_QUOTES|ENT_XML1, 'utf-8') ?></id>
			<content type="text"><?= htmlspecialchars($entry->Description, ENT_QUOTES|ENT_XML1, 'utf-8') ?></content>
		</entry>
	<? } ?>
</feed>
