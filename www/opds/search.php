<?
require_once('Core.php');
use Safe\DateTime;

$now = new DateTime('now', new DateTimeZone('UTC'));
$ebooks = [];

try{
	$query = HttpInput::Str(GET, 'query', false);

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
<feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:schema="http://schema.org/" xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/">
	<id>https://standardebooks.org/opds/all?query=<?= urlencode($query) ?></id>
	<link href="/opds/all?query=<?= urlencode($query) ?>" rel="self" type="application/atom+xml;profile=opds-catalog"/>
	<link href="/ebooks/ebooks?query=doyle" rel="alternate" type="text/html"/>
	<link href="/opds" rel="start" type="application/atom+xml;profile=opds-catalog;kind=navigation"/>
	<link href="/opds/all" rel="http://opds-spec.org/crawlable" type="application/atom+xml;profile=opds-catalog;kind=acquisition"/>
	<link href="/ebooks/opensearch" rel="search" type="application/opensearchdescription+xml"/>
	<title>Standard Ebooks OPDS Search Results</title>
	<subtitle>Free and liberated ebooks, carefully produced for the true book lover.</subtitle>
	<icon>/images/logo.png</icon>
	<updated><?= $now->Format('Y-m-d\TH:i:s\Z') ?></updated>
	<author>
		<name>Standard Ebooks</name>
		<uri>https://standardebooks.org</uri>
	</author>
	<opensearch:totalResults><?= sizeof($ebooks) ?></opensearch:totalResults>
<? foreach($ebooks as $ebook){ ?>
	<?= Template::OpdsAcquisitionEntry(['ebook' => $ebook]) ?>
<? } ?>
</feed>
