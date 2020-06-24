<?

/* Notes:

- *All* OPDS feeds must contain a rel="crawlable" link pointing to the /opds/all feed

- The <fh:complete/> element is required to note this as a "Complete Acquisition Feeds"; see https://specs.opds.io/opds-1.2#25-complete-acquisition-feeds

*/

if(!isset($isCrawlable)){
	$isCrawlable = false;
}

print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n");
?>
<feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:schema="http://schema.org/" xmlns:fh="http://purl.org/syndication/history/1.0">
	<id><?= $id ?></id>
	<link href="<?= $url ?>" rel="self" type="application/atom+xml;profile=opds-catalog;kind=acquisition"/>
	<link href="<?= SITE_URL ?>/opds" rel="start" type="application/atom+xml;profile=opds-catalog;kind=navigation"/>
	<link href="<?= SITE_URL ?>/opds/all" rel="crawlable" type="application/atom+xml;profile=opds-catalog;kind=acquisition"/>
	<link href="<?= SITE_URL ?>/ebooks/opensearch" rel="search" type="application/opensearchdescription+xml" />
	<title><?= $title ?></title>
	<subtitle>Free and liberated ebooks, carefully produced for the true book lover.</subtitle>
	<icon><?= SITE_URL ?>/images/logo.png</icon>
	<updated><?= $updatedTimestamp ?></updated>
	<? if($isCrawlable){ ?><fh:complete/><? } ?>
	<author>
		<name>Standard Ebooks</name>
		<uri><?= SITE_URL ?></uri>
	</author>
	<? foreach($entries as $ebook){ ?>
		<?= Template::OpdsEntry(['ebook' => $ebook]) ?>
	<? } ?>
</feed>
