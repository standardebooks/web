<?
use function Safe\preg_replace;

$page = HttpInput::Int(GET, 'page') ?? 1;
$pages = 0;
$perPage = HttpInput::Int(GET, 'per-page') ?? EBOOKS_PER_PAGE;
$query = HttpInput::Str(GET, 'query') ?? '';
$tags = HttpInput::Array(GET, 'tags') ?? [];
$view = ViewType::tryFrom(HttpInput::Str(GET, 'view') ?? '');
$sort = EbookSortType::tryFrom(HttpInput::Str(GET, 'sort') ?? '');
$queryString = '';
$queryStringParams = [];
$queryStringWithoutPage = '';

try{
	if($page <= 0){
		$page = 1;
	}

	if($perPage != EBOOKS_PER_PAGE && $perPage != 24 && $perPage != 48){
		$perPage = EBOOKS_PER_PAGE;
	}

	// If we're passed string values that are the same as the defaults,
	// set them to null so that we can have cleaner query strings in the navigation footer
	if($view === ViewType::Grid){
		$view = null;
	}

	if($sort == EbookSortType::Newest){
		$sort = null;
	}

	if(sizeof($tags) == 1 && mb_strtolower($tags[0]) == 'all'){
		$tags = [];
	}

	$result = Library::FilterEbooks($query != '' ? $query : null, $tags, $sort, $page, $perPage);
	$ebooks = $result['ebooks'];
	$totalEbooks = $result['ebooksCount'];
	$pageTitle = 'Browse Standard Ebooks';
	$pageHeader = 'Browse Ebooks';
	$pages = ceil($totalEbooks / $perPage);

	if($page > 1){
		$pageTitle .= ', page ' . $page;
	}

	$pageDescription = 'Page ' . $page . ' of the Standard Ebooks free ebook library';

	if($query != ''){
		$queryStringParams['query'] = $query;
	}

	if(sizeof($tags) > 0){
		$queryStringParams['tags'] = $tags;
	}

	if($view !== null){
		$queryStringParams['view'] = $view->value;
	}

	if($sort !== null){
		$queryStringParams['sort'] = $sort->value;
	}

	if($perPage !== EBOOKS_PER_PAGE){
		$queryStringParams['per-page'] = $perPage;
	}

	if($page > 1){
		$queryStringParams['page'] = $page;
	}

	ksort($queryStringParams);

	$queryString = http_build_query($queryStringParams);

	unset($queryStringParams['page']);
	$queryStringWithoutPage = http_build_query($queryStringParams);

	$canonicalUrl = SITE_URL . '/ebooks';

	if($queryString != ''){
		$canonicalUrl .=  '?' . $queryString;
	}

	if($pages > 0 && $page > $pages){
		throw new Exceptions\PageOutOfBoundsException();
	}
}
catch(Exceptions\PageOutOfBoundsException){
	$url = '/ebooks?page=' . $pages;
	if($queryStringWithoutPage != ''){
		$url .= '&' . $queryStringWithoutPage;
	}

	header('Location: ' . $url);
	exit();
}
catch(Exceptions\AppException $ex){
	// Something very unexpected happened, log and emit 500
	http_response_code(500); // Internal server error
	Log::WriteErrorLogEntry($ex);
	exit();
}
?><?= Template::Header(['title' => $pageTitle, 'highlight' => 'ebooks', 'description' => $pageDescription, 'canonicalUrl' => $canonicalUrl]) ?>
<main class="ebooks">
	<h1><?= $pageHeader ?></h1>
	<?= Template::DonationCounter() ?>
	<?= Template::DonationProgress() ?>
	<? if(!DONATION_DRIVE_ON && !DONATION_DRIVE_COUNTER_ON && DONATION_HOLIDAY_ALERT_ON){ ?>
	<?= Template::DonationAlert() ?>
	<? } ?>
	<?= Template::SearchForm(['query' => $query, 'tags' => $tags, 'sort' => $sort, 'view' => $view, 'perPage' => $perPage]) ?>
	<? if(sizeof($ebooks) == 0){ ?>
		<p class="no-results">No ebooks matched your filters.  You can try different filters, or <a href="/ebooks">browse all of our ebooks</a>.</p>
	<? }else{ ?>
		<?= Template::EbookGrid(['ebooks' => $ebooks, 'view' => $view]) ?>
	<? } ?>
	<? if(sizeof($ebooks) > 0){ ?>
		<nav class="pagination">
			<a<? if($page > 1){ ?> href="/ebooks?page=<?= $page - 1 ?><? if($queryStringWithoutPage != ''){ ?>&amp;<?= Formatter::EscapeHtml($queryStringWithoutPage) ?><? } ?>" rel="prev"<? }else{ ?> aria-disabled="true"<? } ?>>Back</a>
			<ol>
			<? for($i = 1; $i < $pages + 1; $i++){ ?>
				<li<? if($page == $i){ ?> class="highlighted"<? } ?>><a href="/ebooks?page=<?= $i ?><? if($queryStringWithoutPage != ''){ ?>&amp;<?= Formatter::EscapeHtml($queryStringWithoutPage) ?><? } ?>"><?= $i ?></a></li>
			<? } ?>
			</ol>
			<a<? if($page < ceil($totalEbooks / $perPage)){ ?> href="/ebooks?page=<?= $page + 1 ?><? if($queryStringWithoutPage != ''){ ?>&amp;<?= Formatter::EscapeHtml($queryStringWithoutPage) ?><? } ?>" rel="next"<? }else{ ?> aria-disabled="true"<? } ?>>Next</a>
		</nav>
	<? } ?>

	<p class="feeds-alert">We also have <a href="/bulk-downloads">bulk ebook downloads</a> and a <a href="/collections">list of collections</a> available, as well as <a href="/feeds">ebook catalog feeds</a> for use directly in your ereader app or RSS reader.</p>
	<? if(sizeof($ebooks) > 0 && $query == '' && sizeof($tags) == 0 && $page == 1){ ?>
		<?= Template::ContributeAlert() ?>
	<? } ?>
</main>
<?= Template::Footer() ?>
