<?
use function Safe\preg_replace;

try{
	$ebooks = [];
	$totalEbooks = 0;
	$pages = 0;
	$query = Http::$Request->QueryString->Get('query') ?? '';
	$page = Http::$Request->QueryString->Get('page', 'int') ?? 1;
	$perPage = Http::$Request->QueryString->Get('per-page', 'int') ?? EBOOKS_PER_PAGE;
	try{
		$feedFormatType = Enums\FeedFormatType::from(Http::$Request->QueryString->Get('feed-format-type') ?? '');
	}
	catch(ValueError){
		throw new Exceptions\NotFoundException();
	}

	if($perPage <= 0){
		$perPage = EBOOKS_PER_PAGE;
	}

	if($perPage > EBOOKS_MAX_PER_PAGE){
		$perPage = EBOOKS_MAX_PER_PAGE;
	}

	if($query != ''){
		$result = Ebook::GetAllByFilter($query, [], Enums\EbookSortType::Relevance, $page, $perPage, Enums\EbookReleaseStatusFilter::Released);

		$ebooks = $result['ebooks'];
		$totalEbooks = $result['ebooksCount'];
		$pages = $result['totalPages'];

		if($pages <= 0){
			$pages = 1;
		}
	}

	switch($feedFormatType){
		case Enums\FeedFormatType::Atom:
			header('content-type: application/atom+xml');
			break;
		case Enums\FeedFormatType::Opds:
			header('content-type: application/atom+xml;profile=opds-catalog');
			break;
		case Enums\FeedFormatType::Rss:
			header('content-type: application/rss+xml');
			break;
	}

	header('content-type: ' . Feed::NegotiateMimeType('/feeds/ ' . $feedFormatType->value . '/'));

	print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<?xml-stylesheet href=\"" . SITE_URL . "/feeds/" . $feedFormatType->value . "/style\" type=\"text/xsl\"?>\n");
}
catch(Exceptions\NotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\PageOutOfBoundsException $ex){
	$url = preg_replace('/([\?&]page=)\-?[0-9]+/iu', '${1}' . $ex->TotalPages, Http::$Request->RelativeUri);
	header('location: ' . $url);
	exit();
}
?>
<? if($feedFormatType == Enums\FeedFormatType::Atom){ ?>
	<feed xmlns="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/" xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/">
		<id><?= SITE_URL ?>/feeds/atom/all?query=<?= urlencode($query) ?>&amp;page=<?= $page ?>&amp;per-page=<?= $perPage ?></id>
		<link href="<?= SITE_URL ?>/feeds/atom/all?query=<?= urlencode($query) ?>&amp;page=<?= $page ?>&amp;per-page=<?= $perPage ?>" rel="self" type="application/atom+xml"/>
		<link rel="first" href="<?= SITE_URL ?>/feeds/atom/all?query=<?= urlencode($query) ?>&amp;page=1&amp;per-page=<?= $perPage ?>" type="application/atom+xml"/>
		<? if($page > 1){ ?>
			<link rel="previous" href="<?= SITE_URL ?>/feeds/atom/all?query=<?= urlencode($query) ?>&amp;page=<?= $page - 1 ?>&amp;per-page=<?= $perPage ?>" type="application/atom+xml"/>
		<? } ?>
		<? if($page < ceil($totalEbooks / $perPage)){ ?>
			<link rel="next" href="<?= SITE_URL ?>/feeds/atom/all?query=<?= urlencode($query) ?>&amp;page=<?= $page + 1 ?>&amp;per-page=<?= $perPage ?>" type="application/atom+xml"/>
		<? } ?>
		<link rel="last" href="<?= SITE_URL ?>/feeds/atom/all?query=<?= urlencode($query) ?>&amp;page=<?= $pages ?>&amp;per-page=<?= $perPage ?>" type="application/atom+xml"/>
		<link rel="search" href="<?= SITE_URL ?>/opensearch" type="application/opensearchdescription+xml" title="Standard Ebooks"/>
		<link href="<?= SITE_URL ?>/ebooks/ebooks?query=<?= urlencode($query) ?>&amp;page=<?= $page ?>&amp;per-page=<?= $perPage ?>" rel="alternate" type="application/xhtml+xml"/>
		<opensearch:totalResults><?= $totalEbooks ?></opensearch:totalResults>
		<opensearch:startIndex><?= (($page - 1) * $perPage) + 1 ?></opensearch:startIndex>
		<opensearch:itemsPerPage><?= $perPage ?></opensearch:itemsPerPage>
		<title>Search Results</title>
		<subtitle>Results for “<?= Formatter::EscapeXml($query) ?>”</subtitle>
		<icon><?= SITE_URL ?>/images/logo.png</icon>
		<updated><?= NOW->format(Enums\DateTimeFormat::Iso->value) ?></updated>
		<author>
			<name>Standard Ebooks</name>
			<uri><?= SITE_URL ?></uri>
		</author>
		<? foreach($ebooks as $ebook){ ?>
			<?= Template::AtomFeedEntry(entry: $ebook) ?>
		<? } ?>
	</feed>
<? } ?>
<? if($feedFormatType == Enums\FeedFormatType::Opds){ ?>
	<feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:schema="http://schema.org/" xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/">
		<id><?= SITE_URL ?>/feeds/opds/all?query=<?= urlencode($query) ?>&amp;page=<?= $page ?>&amp;per-page=<?= $perPage ?></id>
		<link href="<?= SITE_URL ?>/feeds/opds/all?query=<?= urlencode($query) ?>&amp;page=<?= $page ?>&amp;per-page=<?= $perPage ?>" rel="self" type="application/atom+xml;profile=opds-catalog; charset=utf-8"/>
		<link rel="first" href="<?= SITE_URL ?>/feeds/opds/all?query=<?= urlencode($query) ?>&amp;page=1&amp;per-page=<?= $perPage ?>" type="application/atom+xml;profile=opds-catalog; charset=utf-8"/>
		<? if($page > 1){ ?>
			<link rel="previous" href="<?= SITE_URL ?>/feeds/opds/all?query=<?= urlencode($query) ?>&amp;page=<?= $page - 1 ?>&amp;per-page=<?= $perPage ?>" type="application/atom+xml;profile=opds-catalog; charset=utf-8"/>
		<? } ?>
		<? if($page < ceil($totalEbooks / $perPage)){ ?>
			<link rel="next" href="<?= SITE_URL ?>/feeds/opds/all?query=<?= urlencode($query) ?>&amp;page=<?= $page + 1 ?>&amp;per-page=<?= $perPage ?>" type="application/atom+xml;profile=opds-catalog; charset=utf-8"/>
		<? } ?>
		<link rel="last" href="<?= SITE_URL ?>/feeds/opds/all?query=<?= urlencode($query) ?>&amp;page=<?= $pages ?>&amp;per-page=<?= $perPage ?>" type="application/atom+xml;profile=opds-catalog; charset=utf-8"/>
		<link rel="search" href="<?= SITE_URL ?>/opensearch" type="application/opensearchdescription+xml" title="Standard Ebooks"/>
		<link href="<?= SITE_URL ?>/ebooks/ebooks?query=<?= urlencode($query) ?>&amp;page=<?= $page ?>&amp;per-page=<?= $perPage ?>" rel="alternate" type="application/xhtml+xml"/>
		<opensearch:totalResults><?= $totalEbooks ?></opensearch:totalResults>
		<opensearch:startIndex><?= (($page - 1) * $perPage) + 1 ?></opensearch:startIndex>
		<opensearch:itemsPerPage><?= $perPage ?></opensearch:itemsPerPage>
		<link href="<?= SITE_URL ?>/feeds/opds" rel="start" type="application/atom+xml;profile=opds-catalog;kind=navigation; charset=utf-8"/>
		<link href="<?= SITE_URL ?>/feeds/opds/all" rel="http://opds-spec.org/crawlable" type="application/atom+xml;profile=opds-catalog;kind=acquisition; charset=utf-8"/>
		<title>Search Results</title>
		<subtitle>Results for “<?= Formatter::EscapeXml($query) ?>”</subtitle>
		<icon><?= SITE_URL ?>/images/logo.png</icon>
		<updated><?= NOW->format(Enums\DateTimeFormat::Iso->value) ?></updated>
		<author>
			<name>Standard Ebooks</name>
			<uri><?= SITE_URL ?></uri>
		</author>
		<? foreach($ebooks as $ebook){ ?>
			<?= Template::OpdsAcquisitionEntry(entry: $ebook) ?>
		<? } ?>
	</feed>
<? } ?>
<? if($feedFormatType == Enums\FeedFormatType::Rss){ ?>
	<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/" xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/">
		<channel>
			<title>Search Results</title>
			<link><?= SITE_URL ?></link>
			<description>Results for “<?= Formatter::EscapeXml($query) ?>”</description>
			<language>en-US</language>
			<copyright>https://creativecommons.org/publicdomain/zero/1.0/</copyright>
			<lastBuildDate><?= NOW->format(Enums\DateTimeFormat::Rss->value) ?></lastBuildDate>
			<docs>http://blogs.law.harvard.edu/tech/rss</docs>
			<atom:link href="<?= SITE_URL ?>/feeds/atom/all?query=<?= urlencode($query) ?>&amp;page=<?= $page ?>&amp;per-page=<?= $perPage ?>" rel="self" type="application/atom+xml"/>
			<atom:link rel="first" href="<?= SITE_URL ?>/feeds/atom/all?query=<?= urlencode($query) ?>&amp;page=1&amp;per-page=<?= $perPage ?>" type="application/atom+xml"/>
			<? if($page > 1){ ?>
				<atom:link rel="previous" href="<?= SITE_URL ?>/feeds/atom/all?query=<?= urlencode($query) ?>&amp;page=<?= $page - 1 ?>&amp;per-page=<?= $perPage ?>" type="application/atom+xml"/>
			<? } ?>
			<? if($page < ceil($totalEbooks / $perPage)){ ?>
				<atom:link rel="next" href="<?= SITE_URL ?>/feeds/atom/all?query=<?= urlencode($query) ?>&amp;page=<?= $page + 1 ?>&amp;per-page=<?= $perPage ?>" type="application/atom+xml"/>
			<? } ?>
			<atom:link rel="last" href="<?= SITE_URL ?>/feeds/atom/all?query=<?= urlencode($query) ?>&amp;page=<?= $pages ?>&amp;per-page=<?= $perPage ?>" type="application/atom+xml"/>
			<atom:link rel="search" href="<?= SITE_URL ?>/opensearch" type="application/opensearchdescription+xml" title="Standard Ebooks"/>
			<atom:link href="<?= SITE_URL ?>/ebooks/ebooks?query=<?= urlencode($query) ?>&amp;page=<?= $page ?>&amp;per-page=<?= $perPage ?>" rel="alternate" type="application/xhtml+xml"/>
			<opensearch:totalResults><?= $totalEbooks ?></opensearch:totalResults>
			<opensearch:startIndex><?= (($page - 1) * $perPage) + 1 ?></opensearch:startIndex>
			<opensearch:itemsPerPage><?= $perPage ?></opensearch:itemsPerPage>
			<image>
				<url><?= SITE_URL ?>/images/logo-rss.png</url>
				<title>Search Results</title> <? /* must be identical to channel title */ ?>
				<description>The Standard Ebooks logo</description>
				<link><?= SITE_URL ?></link>
				<height>144</height>
				<width>144</width>
			</image>
			<? foreach($ebooks as $ebook){ ?>
				<?= Template::RssEntry(entry: $ebook) ?>
			<? } ?>
		</channel>
	</rss>
<? } ?>
