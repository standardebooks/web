<?
require_once('Core.php');

$now = new DateTime('now', new DateTimeZone('UTC'));

try{
	$query = HttpInput::GetString('query', false);

	if($query !== null){
		$ebooks = Library::Search($query);
	}
}
catch(\Exception $ex){
	http_response_code(500);
	include(WEB_ROOT . '/404.php');
	exit();
}
header('Content-type: text/xml');
print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n");
?>
<feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:schema="http://schema.org/" xmlns:fh="http://purl.org/syndication/history/1.0">
	<id>https://standardebooks.org/opds/all</id>
	<link href="/opds/all?query=<?= urlencode($query) ?>" rel="self" type="application/atom+xml;profile=opds-catalog"/>
	<link href="/ebooks/ebooks?query=doyle" rel="alternate" type="text/html"/>
	<link href="https://standardebooks.org/opds" rel="start" type="application/atom+xml;profile=opds-catalog;kind=navigation"/>
	<link href="https://standardebooks.org/opds/all" rel="crawlable" type="application/atom+xml;profile=opds-catalog;kind=acquisition"/>
	<link href="https://standardebooks.org/ebooks/opensearch" rel="search" type="application/opensearchdescription+xml" />
	<title>Standard Ebooks OPDS Search Results</title>
	<subtitle>Free and liberated ebooks, carefully produced for the true book lover.</subtitle>
	<icon>https://standardebooks.org/images/logo.png</icon>
	<updated><?= $now->Format('Y-m-d\TH:i:s\Z') ?></updated>
	<author>
		<name>Standard Ebooks</name>
		<uri>https://standardebooks.org</uri>
	</author>
<? foreach($ebooks as $ebook){ ?>
	<?= Template::OpdsEntry(['ebook' => $ebook]) ?>
<? } ?>
</feed>
