<?
use function Safe\preg_match;
use function Safe\preg_replace;

$page = HttpInput::Int(GET, 'page') ?? 1;
$pages = 0;
$perPage = HttpInput::Int(GET, 'per-page') ?? EBOOKS_PER_PAGE;
$query = HttpInput::Str(GET, 'query') ?? '';
$tags = HttpInput::Array(GET, 'tags') ?? [];
$view = Enums\ViewType::tryFrom(HttpInput::Str(GET, 'view') ?? '') ?? Enums\ViewType::Grid;
$sort = Enums\EbookSortType::tryFrom(HttpInput::Str(GET, 'sort') ?? '') ?? Enums\EbookSortType::Default;
$queryString = '';
$queryStringParams = [];
$queryStringWithoutPage = '';
$pageUrl = '/ebooks';

try{
	if($page <= 0){
		$page = 1;
	}

	if($perPage != EBOOKS_PER_PAGE && $perPage != 24 && $perPage != 48){
		$perPage = EBOOKS_PER_PAGE;
	}

	if($sort == Enums\EbookSortType::Default){
		if($query != ''){
			$sort = Enums\EbookSortType::Relevance;
		}
		else{
			$sort = Enums\EbookSortType::Newest;
		}
	}

	// Malformed query: Can't sort by `Relevance` if `$query` is empty.
	// This could happen if the user was looking at `Relevance` results, then deleted the query and hit the Filter button.
	if($sort == Enums\EbookSortType::Relevance && $query == ''){
		$sort = Enums\EbookSortType::Newest;
	}

	if(($sort == Enums\EbookSortType::Newest && $query == '') || ($sort == Enums\EbookSortType::Relevance && $query != '')){
		$sort = Enums\EbookSortType::Default;
	}

	if(sizeof($tags) == 1 && mb_strtolower($tags[0]) == 'all'){
		$tags = [];
	}

	$pageDescription = 'Page ' . $page . ' of the Standard Ebooks free ebook library';

	if($query != ''){
		$queryStringParams['query'] = $query;
	}

	if(sizeof($tags) > 0){
		$queryStringParams['tags'] = $tags;
	}

	// If we're passed string values that are the same as the defaults, don't include them in the query string so that we can have cleaner query strings in the navigation footer.
	if($view != Enums\ViewType::Grid){
		$queryStringParams['view'] = $view->value;
	}

	if($sort != Enums\EbookSortType::Default){
		$queryStringParams['sort'] = $sort->value;
	}

	if($perPage !== EBOOKS_PER_PAGE){
		$queryStringParams['per-page'] = $perPage;
	}

	if($page > 1){
		$queryStringParams['page'] = $page;
	}

	ksort($queryStringParams);

	// If all we did was select one tag, redirect the user to `/subjects/<TAG>` instead of `/ebooks?tag[0]=<TAG>`.
	/** @var string $requestUri */
	$requestUri = $_SERVER['REQUEST_URI'] ?? '';
	if(sizeof($tags) == 1 && $query == '' && preg_match('|^/ebooks|iu', $requestUri)){
		unset($queryStringParams['tags']);
		$queryStringWithoutTags = http_build_query($queryStringParams);
		$url = '/subjects/' . $tags[0];
		if($queryStringWithoutTags != ''){
			$url .= '?' . $queryStringWithoutTags;
		}
		header('Location: ' . $url);
		exit();
	}

	// We only have one tag, change the page URL used for back/next links to `/subjects/<TAG>`.
	if(sizeof($tags) == 1 && $query == ''){
		$pageUrl = '/subjects/' . $tags[0];
		unset($queryStringParams['tags']);
	}

	$queryString = http_build_query($queryStringParams);

	unset($queryStringParams['page']);
	$queryStringWithoutPage = http_build_query($queryStringParams);

	$canonicalUrl = SITE_URL . $pageUrl;

	if($queryString != ''){
		$canonicalUrl .= '?' . $queryString;
	}

	$result = Ebook::GetAllByFilter($query != '' ? $query : null, $tags, $sort, $page, $perPage, Enums\EbookReleaseStatusFilter::All);
	$ebooks = $result['ebooks'];
	$totalEbooks = $result['ebooksCount'];
	$pageTitle = 'Browse Standard Ebooks';
	$pageHeader = 'Browse Ebooks';
	$pages = ceil($totalEbooks / $perPage);

	if($page > 1){
		$pageTitle .= ', page ' . $page;
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
?><?= Template::Header(title: $pageTitle, highlight: 'ebooks', description: $pageDescription, canonicalUrl: $canonicalUrl) ?>
<main class="ebooks">
	<h1><?= $pageHeader ?></h1>
	<?= Template::DonationCounter() ?>
	<?= Template::DonationProgress() ?>

	<?= Template::DonationAlert() ?>

	<?= Template::SearchForm(query: $query, tags: $tags, sort: $sort, view: $view, perPage: $perPage) ?>

	<? if(sizeof($ebooks) == 0){ ?>
		<p class="no-results">No ebooks matched your filters. You can try different filters, or <a href="/ebooks">browse all of our ebooks</a>.</p>
	<? }else{ ?>
		<?= Template::EbookGrid(ebooks: $ebooks, view: $view) ?>
	<? } ?>
	<? if(sizeof($ebooks) > 0){ ?>
		<nav class="pagination">
			<a<? if($page > 1){ ?> href="<?= $pageUrl ?>?page=<?= $page - 1 ?><? if($queryStringWithoutPage != ''){ ?>&amp;<?= Formatter::EscapeHtml($queryStringWithoutPage) ?><? } ?>" rel="prev"<? }else{ ?> aria-disabled="true"<? } ?>>Back</a>
			<ol>
			<? for($i = 1; $i < $pages + 1; $i++){ ?>
				<li<? if($page == $i){ ?> class="highlighted"<? } ?>>
					<a href="<?= $pageUrl ?>?page=<?= $i ?><? if($queryStringWithoutPage != ''){ ?>&amp;<?= Formatter::EscapeHtml($queryStringWithoutPage) ?><? } ?>"><?= $i ?></a>
				</li>
			<? } ?>
			</ol>
			<a<? if($page < ceil($totalEbooks / $perPage)){ ?> href="<?= $pageUrl ?>?page=<?= $page + 1 ?><? if($queryStringWithoutPage != ''){ ?>&amp;<?= Formatter::EscapeHtml($queryStringWithoutPage) ?><? } ?>" rel="next"<? }else{ ?> aria-disabled="true"<? } ?>>Next</a>
		</nav>
	<? } ?>

	<p class="feeds-alert">We also have <a href="/bulk-downloads">bulk ebook downloads</a> and a <a href="/collections">list of collections</a> available, as well as <a href="/feeds">ebook catalog feeds</a> for use directly in your ereader app or RSS reader.</p>
	<? if(sizeof($ebooks) > 0 && $query == '' && sizeof($tags) == 0 && $page == 1){ ?>
		<?= Template::ContributeAlert() ?>
	<? } ?>
</main>
<?= Template::Footer() ?>
