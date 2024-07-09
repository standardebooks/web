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
print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<?xml-stylesheet href=\"" . SITE_URL . "/feeds/rss/style\" type=\"text/xsl\"?>\n");
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/" xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/">
	<channel>
		<title>Search Results</title>
		<link><?= SITE_URL ?></link>
		<description>Results for “<?= Formatter::EscapeXml($query) ?>”.</description>
		<language>en-US</language>
		<copyright>https://creativecommons.org/publicdomain/zero/1.0/</copyright>
		<lastBuildDate><?= (new DateTimeImmutable())->format('r') ?></lastBuildDate>
		<docs>http://blogs.law.harvard.edu/tech/rss</docs>
		<atom:link href="<?= SITE_URL ?>/feeds/rss/all?query=<?= urlencode($query) ?>" rel="self" type="application/rss+xml"/>
		<atom:link href="<?= SITE_URL ?>/ebooks/opensearch" rel="search" type="application/opensearchdescription+xml" />
		<image>
			<url><?= SITE_URL ?>/images/logo-rss.png</url>
			<title>Search Results</title> <? /* must be identical to channel title */ ?>
			<description>The Standard Ebooks logo</description>
			<link><?= SITE_URL ?></link>
			<height>144</height>
			<width>144</width>
		</image>
		<opensearch:totalResults><?= sizeof($ebooks) ?></opensearch:totalResults>
		<? foreach($ebooks as $ebook){ ?>
			<?= Template::RssEntry(['entry' => $ebook]) ?>
		<? } ?>
	</channel>
</rss>
