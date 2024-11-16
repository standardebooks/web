<?
$page = HttpInput::Int(GET, 'page') ?? 1;
$perPage = HttpInput::Int(GET, 'per-page') ?? ARTWORK_PER_PAGE;
$query = HttpInput::Str(GET, 'query');
$queryEbookUrl = HttpInput::Str(GET, 'query-ebook-url');
$status = HttpInput::Str(GET, 'status');
$filterArtworkStatus = $status;
$sort = Enums\ArtworkSortType::tryFrom(HttpInput::Str(GET, 'sort') ?? '');
$pages = 0;
$totalArtworkCount = 0;
$pageDescription = '';
$pageTitle = '';
$queryString = '';
$isReviewerView = Session::$User?->Benefits?->CanReviewArtwork ?? false;
$submitterUserId = Session::$User?->Benefits?->CanUploadArtwork ? Session::$User->UserId : null;
$isSubmitterView = !$isReviewerView && $submitterUserId !== null;

try{
	if($page <= 0){
		$page = 1;
	}

	if($perPage != ARTWORK_PER_PAGE && $perPage != 40 && $perPage != 80){
		$perPage = ARTWORK_PER_PAGE;
	}

	// If we're passed string values that are the same as the defaults, set them to null so that we can have cleaner query strings in the navigation footer.
	if($sort == Enums\ArtworkSortType::CreatedNewest){
		$sort = null;
	}

	if($isReviewerView){
		if($status == 'all' || $status === null){
			$filterArtworkStatus = 'all-admin';
		}
	}

	if($isSubmitterView){
		if($status == 'all' || $status === null){
			$filterArtworkStatus = 'all-submitter';
		}
		if($status == 'unverified'){
			$filterArtworkStatus = 'unverified-submitter';
		}
	}

	if(!$isReviewerView && !$isSubmitterView && !in_array($status, array('all', Enums\ArtworkStatusType::Approved->value, 'in-use'))){
		$status = Enums\ArtworkStatusType::Approved->value;
		$filterArtworkStatus = $status;
	}

	if($isReviewerView && !in_array($status, array('all', Enums\ArtworkStatusType::Unverified->value, Enums\ArtworkStatusType::Declined->value, Enums\ArtworkStatusType::Approved->value, 'in-use'))
	                && !in_array($filterArtworkStatus, array('all-admin', Enums\ArtworkStatusType::Unverified->value, Enums\ArtworkStatusType::Declined->value, Enums\ArtworkStatusType::Approved->value, 'in-use'))){
		$status = Enums\ArtworkStatusType::Approved->value;
		$filterArtworkStatus = $status;
	}

	if($isSubmitterView && !in_array($status, array('all', Enums\ArtworkStatusType::Unverified->value, Enums\ArtworkStatusType::Approved->value, 'in-use'))
	                    && !in_array($filterArtworkStatus, array('all-submitter', 'unverified-submitter', Enums\ArtworkStatusType::Approved->value, 'in-use'))){
		$status = Enums\ArtworkStatusType::Approved->value;
		$filterArtworkStatus = $status;
	}

	if($queryEbookUrl !== null){
		$artworks = Db::Query('SELECT * from Artworks where EbookUrl = ? and Status = ? limit 1', [$queryEbookUrl, Enums\ArtworkStatusType::Approved], Artwork::class);
		$totalArtworkCount = sizeof($artworks);
	}
	else{
		$result = Artwork::GetAllByFilter($query, $filterArtworkStatus, $sort, $submitterUserId, $page, $perPage);
		$artworks = $result['artworks'];
		$totalArtworkCount = $result['artworksCount'];
	}

	$pageTitle = 'Browse Artwork';
	if($page > 1){
		$pageTitle .= ', page ' . $page;
	}

	$pageDescription = 'Page ' . $page . ' of artwork';

	$queryStringParams = [];

	if($query !== null && $query != ''){
		$queryStringParams['query'] = $query;
	}

	if($status !== null){
		$queryStringParams['status'] = $status;
	}

	if($sort !== null){
		$queryStringParams['sort'] = $sort->value;
	}

	if($perPage !== ARTWORK_PER_PAGE){
		$queryStringParams['per-page'] = $perPage;
	}

	if($page > 1){
		$queryStringParams['page'] = $page;
	}

	ksort($queryStringParams);

	$queryString = http_build_query($queryStringParams);

	unset($queryStringParams['page']);
	$queryStringWithoutPage = http_build_query($queryStringParams);

	$canonicalUrl = SITE_URL . '/artworks';

	if($queryString != ''){
		$canonicalUrl .=  '?' . $queryString;
	}

	$pages = ceil($totalArtworkCount / $perPage);
	if($pages > 0 && $page > $pages){
		throw new Exceptions\PageOutOfBoundsException();
	}
}
catch(Exceptions\PageOutOfBoundsException){
	$url = '/artworks?page=' . $pages;
	if($queryStringWithoutPage != ''){
		$url .= '&' . $queryStringWithoutPage;
	}

	header('Location: ' . $url);
	exit();
}
?><?= Template::Header(['title' => $pageTitle, 'artwork' => true, 'description' => $pageDescription, 'canonicalUrl' => $canonicalUrl]) ?>
<main class="artworks">
	<section class="narrow">
		<h1>Browse U.S. Public Domain Artwork</h1>
		<p><? if(Session::$User?->Benefits->CanUploadArtwork){ ?><a href="/artworks/new">Submit new public domain artwork.</a><? }else{ ?>You can help Standard Ebooks by <a href="/artworks/new">submitting new public domain artwork</a> to add to this catalog for use in future ebooks. For free access to the submission form, <a href="/about#editor-in-chief">contact the Editor-in-Chief</a>.<? } ?></p>
		<form class="browse-artwork" action="/artworks" method="get" rel="search">
			<label>
				<span>Status</span>
				<span>
					<select name="status" size="1">
						<option value="all"<? if($status === null){ ?> selected="selected"<? } ?>>All</option>
						<? if($isReviewerView || $isSubmitterView){ ?><option value="<?= Enums\ArtworkStatusType::Unverified->value ?>"<? if($status == Enums\ArtworkStatusType::Unverified->value){ ?> selected="selected"<? } ?>>Unverified</option><? } ?>
						<? if($isReviewerView){ ?><option value="<?= Enums\ArtworkStatusType::Declined->value ?>"<? if($status == Enums\ArtworkStatusType::Declined->value){ ?> selected="selected"<? } ?>>Declined</option><? } ?>
						<option value="<?= Enums\ArtworkStatusType::Approved->value ?>"<? if($status == Enums\ArtworkStatusType::Approved->value){ ?> selected="selected"<? } ?>>Approved, not in use</option>
						<option value="in-use"<? if($status == 'in-use'){ ?> selected="selected"<? } ?>>In use</option>
					</select>
				</span>
			</label>
			<label>Keywords
				<input type="search" name="query" value="<?= Formatter::EscapeHtml($query) ?>"/>
			</label>
			<label class="sort">
				<span>Sort</span>
				<span>
					<select name="sort">
						<option value="<?= Enums\ArtworkSortType::CreatedNewest->value ?>"<? if($sort == Enums\ArtworkSortType::CreatedNewest){ ?> selected="selected"<? } ?>>Date added (new &#x2192; old)</option>
						<option value="<?= Enums\ArtworkSortType::ArtistAlpha->value ?>"<? if($sort == Enums\ArtworkSortType::ArtistAlpha){ ?> selected="selected"<? } ?>>Artist name (a &#x2192; z)</option>
						<option value="<?= Enums\ArtworkSortType::CompletedNewest->value ?>"<? if($sort == Enums\ArtworkSortType::CompletedNewest){ ?> selected="selected"<? } ?>>Date of artwork completion (new &#x2192; old)</option>
					</select>
				</span>
			</label>
			<label>
				<span>Per page</span>
				<span>
					<select name="per-page">
						<option value="20"<? if($perPage == 20){ ?> selected="selected"<? } ?>>20</option>
						<option value="40"<? if($perPage == 40){ ?> selected="selected"<? } ?>>40</option>
						<option value="80"<? if($perPage == 80){ ?> selected="selected"<? } ?>>80</option>
					</select>
				</span>
			</label>
			<button>Filter</button>
		</form>

		<?= Template::ImageCopyrightNotice() ?>

		<? if($totalArtworkCount == 0){ ?>
			<p class="no-results">No artwork matched your filters.  You can try different filters, or <a href="/artworks">browse all artwork</a>.</p>
		<? }else{ ?>
			<?= Template::ArtworkList(['artworks' => $artworks]) ?>
		<? } ?>

		<? if($totalArtworkCount > 0){ ?>
			<nav class="pagination">
				<a<? if($page > 1){ ?> href="/artworks?page=<?= $page - 1 ?><? if($queryStringWithoutPage != ''){ ?>&amp;<?= Formatter::EscapeHtml($queryStringWithoutPage) ?><? } ?>" rel="prev"<? }else{ ?> aria-disabled="true"<? } ?>>Back</a>
				<ol>
				<? for($i = 1; $i < $pages + 1; $i++){ ?>
					<li<? if($page == $i){ ?> class="highlighted"<? } ?>><a href="/artworks?page=<?= $i ?><? if($queryStringWithoutPage != ''){ ?>&amp;<?= Formatter::EscapeHtml($queryStringWithoutPage) ?><? } ?>"><?= $i ?></a></li>
				<? } ?>
				</ol>
				<a<? if($page < ceil($totalArtworkCount / $perPage)){ ?> href="/artworks?page=<?= $page + 1 ?><? if($queryStringWithoutPage != ''){ ?>&amp;<?= Formatter::EscapeHtml($queryStringWithoutPage) ?><? } ?>" rel="next"<? }else{ ?> aria-disabled="true"<? } ?>>Next</a>
			</nav>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
