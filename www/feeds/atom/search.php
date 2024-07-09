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
	include(WEB_ROOT . '/404.php');
	exit();
}
print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<?xml-stylesheet href=\"" . SITE_URL . "/feeds/atom/style\" type=\"text/xsl\"?>\n");
?>
<feed xmlns="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/" xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/">
	<id><?= SITE_URL ?>/feeds/atom/all?query=<?= urlencode($query) ?></id>
	<link href="<?= SITE_URL ?>/feeds/atom/all?query=<?= urlencode($query) ?>" rel="self" type="application/atom+xml"/>
	<link href="<?= SITE_URL ?>/ebooks/ebooks?query=<?= urlencode($query) ?>" rel="alternate" type="text/html"/>
	<link href="<?= SITE_URL ?>/ebooks/opensearch" rel="search" type="application/opensearchdescription+xml"/>
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
	<?= Template::AtomFeedEntry(['entry' => $ebook]) ?>
<? } ?>
</feed>
