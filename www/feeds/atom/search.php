<?
$ebooks = [];
$query = HttpInput::Str(GET, 'query') ?? '';
$startPage = HttpInput::Int(GET, 'page') ?? 1;
$count = HttpInput::Int(GET, 'per-page') ?? EBOOKS_PER_PAGE;

if($query !== ''){
	$ebooks = Ebook::GetAllByFilter($query, [], Enums\EbookSortType::Newest, $startPage, $count, Enums\EbookReleaseStatusFilter::Released)['ebooks'];
}

print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<?xml-stylesheet href=\"" . SITE_URL . "/feeds/atom/style\" type=\"text/xsl\"?>\n");
?>
<feed xmlns="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/" xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/">
	<id><?= SITE_URL ?>/feeds/atom/all?query=<?= urlencode($query) ?></id>
	<link href="<?= SITE_URL ?>/feeds/atom/all?query=<?= urlencode($query) ?>" rel="self" type="application/atom+xml"/>
	<link href="<?= SITE_URL ?>/opensearch" rel="search" type="application/opensearchdescription+xml" title="Standard Ebooks"/>
	<title>Search Results</title>
	<subtitle>Results for “<?= Formatter::EscapeXml($query) ?>”.</subtitle>
	<icon><?= SITE_URL ?>/images/logo.png</icon>
	<updated><?= NOW->format(Enums\DateTimeFormat::Iso->value) ?></updated>
	<author>
		<name>Standard Ebooks</name>
		<uri><?= SITE_URL ?></uri>
	</author>
	<opensearch:totalResults><?= sizeof($ebooks) ?></opensearch:totalResults>
	<? foreach($ebooks as $ebook){ ?>
		<?= Template::AtomFeedEntry(entry: $ebook) ?>
	<? } ?>
</feed>
