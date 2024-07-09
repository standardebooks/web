<?
use Safe\DateTimeImmutable;

$ebooks = [];

try{
	$query = HttpInput::Str(GET, 'query') ?? '';
	$startPage = HttpInput::Int(GET, 'page') ?? 1;
	$count = HttpInput::Int(GET, 'per-page') ?? EBOOKS_PER_PAGE;

	if($query !== ''){
		$ebooks = Library::FilterEbooks($query, [], EbookSortType::Newest, $startPage, $count)['ebooks'];
	}
}
catch(\Exception){
	http_response_code(500);
	exit();
}
print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<?xml-stylesheet href=\"". SITE_URL . "/feeds/opds/style\" type=\"text/xsl\"?>\n");
?>
<feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:schema="http://schema.org/" xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/">
	<id><?= SITE_URL ?>/feeds/opds/all?query=<?= urlencode($query) ?></id>
	<link href="<?= SITE_URL ?>/feeds/opds/all?query=<?= urlencode($query) ?>" rel="self" type="application/atom+xml;profile=opds-catalog; charset=utf-8"/>
	<link href="<?= SITE_URL ?>/ebooks/ebooks?query=<?= urlencode($query) ?>" rel="alternate" type="text/html"/>
	<link href="<?= SITE_URL ?>/feeds/opds" rel="start" type="application/atom+xml;profile=opds-catalog;kind=navigation; charset=utf-8"/>
	<link href="<?= SITE_URL ?>/feeds/opds/all" rel="http://opds-spec.org/crawlable" type="application/atom+xml;profile=opds-catalog;kind=acquisition; charset=utf-8"/>
	<link href="<?= SITE_URL ?>/ebooks/opensearch" rel="search" type="application/opensearchdescription+xml; charset=utf-8"/>
	<title>Search Results</title>
	<subtitle>Results for “<?= Formatter::EscapeXml($query) ?>”.</subtitle>
	<icon><?= SITE_URL ?>/images/logo.png</icon>
	<updated><?= (new DateTimeImmutable())->Format('Y-m-d\TH:i:s\Z') ?></updated>
	<author>
		<name>Standard Ebooks</name>
		<uri><?= SITE_URL ?></uri>
	</author>
	<opensearch:totalResults><?= sizeof($ebooks) ?></opensearch:totalResults>
<? foreach($ebooks as $ebook){ ?>
	<?= Template::OpdsAcquisitionEntry(['entry' => $ebook]) ?>
<? } ?>
</feed>
