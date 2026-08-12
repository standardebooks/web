<?
try{
	$query = Http::$Request->QueryString->Get('query') ?? '';
	$page = Http::$Request->QueryString->Get('page', 'int') ?? 1;
	$perPage = Http::$Request->QueryString->Get('per-page', 'int') ?? EBOOKS_PER_PAGE;
	try{
		$feedFormatType = Enums\FeedFormatType::from(Http::$Request->QueryString->Get('feed-format-type') ?? '');
	}
	catch(ValueError){
		throw new Exceptions\NotFoundException();
	}

	if($perPage <= 0 || $perPage > RESULTS_MAX_PER_PAGE){
		$perPage = EBOOKS_PER_PAGE;
	}

	if($perPage > EBOOKS_MAX_PER_PAGE){
		$perPage = EBOOKS_MAX_PER_PAGE;
	}

	$result = new PaginatedResultsPage([], 1, 0, $perPage);

	if($query != ''){
		$result = Ebook::GetAllByFilter($query, [], Enums\EbookSortType::Relevance, $page, $perPage, Enums\EbookReleaseStatusFilter::Released);

		if($result->TotalPages <= 0){
			$result->TotalPages = 1;
		}
	}

	switch($feedFormatType){
		case Enums\FeedFormatType::Atom:
			header('content-type: application/atom+xml');
			break;
		case Enums\FeedFormatType::Opds:
			// Use a hard-coded throwaway path to trigger OPDS MIME type negotation.
			$targetMimeType = Feed::NegotiateMimeType('/feeds/opds');

			if($targetMimeType == 'application/opds+json; charset=utf-8'){
				$searchFeedUrl = '/feeds/opds/all?query=' . urlencode($query) . '&page=' . $result->Page . '&per-page=' . $perPage;
				$searchFeed = new OpdsAcquisitionFeed('Search Results', 'Results for “' . $query . '”', $searchFeedUrl, '', $result->Results, null);
				$searchFeed->Updated = NOW;
				header('content-type: ' . $targetMimeType);
				print($searchFeed->ToJsonString());
				exit();
			}

			header('content-type: application/atom+xml;profile=opds-catalog');
			break;
	}

	header('content-type: ' . Feed::NegotiateMimeType('/feeds/' . $feedFormatType->value . '/'));

	print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<?xml-stylesheet href=\"" . SITE_URL . "/feeds/" . $feedFormatType->value . "/style\" type=\"text/xsl\"?>\n");
}
catch(Exceptions\NotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\PageOutOfBoundsException $ex){
	Template::RedirectToResultsPage($ex->RealPageNumber);
}
?>
<? if($feedFormatType == Enums\FeedFormatType::Atom){ ?>
	<feed xmlns="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/" xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/">
		<id><?= SITE_URL ?>/feeds/atom/all?query=<?= urlencode($query) ?>&amp;page=<?= $result->Page ?>&amp;per-page=<?= $perPage ?></id>
		<link href="<?= SITE_URL ?>/feeds/atom/all?query=<?= urlencode($query) ?>&amp;page=<?= $result->Page ?>&amp;per-page=<?= $perPage ?>" rel="self" type="application/atom+xml"/>
		<link rel="first" href="<?= SITE_URL ?>/feeds/atom/all?query=<?= urlencode($query) ?>&amp;page=1&amp;per-page=<?= $perPage ?>" type="application/atom+xml"/>
		<? if($result->Page > 1){ ?>
			<link rel="previous" href="<?= SITE_URL ?>/feeds/atom/all?query=<?= urlencode($query) ?>&amp;page=<?= $result->Page - 1 ?>&amp;per-page=<?= $perPage ?>" type="application/atom+xml"/>
		<? } ?>
		<? if($result->Page < $result->TotalPages){ ?>
			<link rel="next" href="<?= SITE_URL ?>/feeds/atom/all?query=<?= urlencode($query) ?>&amp;page=<?= $result->Page + 1 ?>&amp;per-page=<?= $perPage ?>" type="application/atom+xml"/>
		<? } ?>
		<link rel="last" href="<?= SITE_URL ?>/feeds/atom/all?query=<?= urlencode($query) ?>&amp;page=<?= $result->TotalPages ?>&amp;per-page=<?= $perPage ?>" type="application/atom+xml"/>
		<link rel="search" href="<?= SITE_URL ?>/opensearch" type="application/opensearchdescription+xml" title="Standard Ebooks"/>
		<link href="<?= SITE_URL ?>/ebooks/ebooks?query=<?= urlencode($query) ?>&amp;page=<?= $result->Page ?>&amp;per-page=<?= $perPage ?>" rel="alternate" type="application/xhtml+xml"/>
		<opensearch:totalResults><?= $result->TotalResults ?></opensearch:totalResults>
		<opensearch:startIndex><?= (($result->Page - 1) * $perPage) + 1 ?></opensearch:startIndex>
		<opensearch:itemsPerPage><?= $perPage ?></opensearch:itemsPerPage>
		<title>Search Results</title>
		<subtitle>Results for “<?= Formatter::EscapeXml($query) ?>”</subtitle>
		<icon><?= SITE_URL ?>/images/logo.png</icon>
		<updated><?= NOW->format(Enums\DateTimeFormat::Iso->value) ?></updated>
		<author>
			<name>Standard Ebooks</name>
			<uri><?= SITE_URL ?></uri>
		</author>
		<? foreach($result->Results as $ebook){ ?>
			<?= Template::AtomFeedEntry(entry: $ebook) ?>
		<? } ?>
	</feed>
<? } ?>
<? if($feedFormatType == Enums\FeedFormatType::Opds){ ?>
	<feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:schema="http://schema.org/" xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/">
		<id><?= SITE_URL ?>/feeds/opds/all?query=<?= urlencode($query) ?>&amp;page=<?= $result->Page ?>&amp;per-page=<?= $perPage ?></id>
		<link href="<?= SITE_URL ?>/feeds/opds/all?query=<?= urlencode($query) ?>&amp;page=<?= $result->Page ?>&amp;per-page=<?= $perPage ?>" rel="self" type="application/atom+xml;profile=opds-catalog; charset=utf-8"/>
		<link rel="first" href="<?= SITE_URL ?>/feeds/opds/all?query=<?= urlencode($query) ?>&amp;page=1&amp;per-page=<?= $perPage ?>" type="application/atom+xml;profile=opds-catalog; charset=utf-8"/>
		<? if($result->Page > 1){ ?>
			<link rel="previous" href="<?= SITE_URL ?>/feeds/opds/all?query=<?= urlencode($query) ?>&amp;page=<?= $result->Page - 1 ?>&amp;per-page=<?= $perPage ?>" type="application/atom+xml;profile=opds-catalog; charset=utf-8"/>
		<? } ?>
		<? if($result->Page < $result->TotalPages){ ?>
			<link rel="next" href="<?= SITE_URL ?>/feeds/opds/all?query=<?= urlencode($query) ?>&amp;page=<?= $result->Page + 1 ?>&amp;per-page=<?= $perPage ?>" type="application/atom+xml;profile=opds-catalog; charset=utf-8"/>
		<? } ?>
		<link rel="last" href="<?= SITE_URL ?>/feeds/opds/all?query=<?= urlencode($query) ?>&amp;page=<?= $result->TotalPages ?>&amp;per-page=<?= $perPage ?>" type="application/atom+xml;profile=opds-catalog; charset=utf-8"/>
		<link rel="search" href="<?= SITE_URL ?>/opensearch" type="application/opensearchdescription+xml" title="Standard Ebooks"/>
		<link href="<?= SITE_URL ?>/ebooks/ebooks?query=<?= urlencode($query) ?>&amp;page=<?= $result->Page ?>&amp;per-page=<?= $perPage ?>" rel="alternate" type="application/xhtml+xml"/>
		<opensearch:totalResults><?= $result->TotalResults ?></opensearch:totalResults>
		<opensearch:startIndex><?= (($result->Page - 1) * $perPage) + 1 ?></opensearch:startIndex>
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
		<? foreach($result->Results as $ebook){ ?>
			<?= Template::OpdsAcquisitionEntry(entry: $ebook) ?>
		<? } ?>
	</feed>
<? } ?>
