<?
$page = HttpInput::Int(GET, 'page') ?? 1;
$perPage = HttpInput::Int(GET, 'per-page') ?? ARTWORK_PER_PAGE;
$query = HttpInput::Str(GET, 'query');
$queryEbookUrl = HttpInput::Str(GET, 'query-ebook-url');
$status = HttpInput::Str(GET, 'status');
$filterArtworkStatus = $status;
$sort = ArtworkSort::tryFrom(HttpInput::Str(GET, 'sort') ?? '');
$pages = 0;
$totalArtworkCount = 0;
$pageDescription = '';
$pageTitle = '';
$queryString = '';
$isReviewerView = $GLOBALS['User']?->Benefits?->CanReviewArtwork ?? false;
$submitterUserId = $GLOBALS['User']?->Benefits?->CanUploadArtwork ? $GLOBALS['User']->UserId : null;
$isSubmitterView = !$isReviewerView && $submitterUserId !== null;

try{
	if($page <= 0){
		$page = 1;
	}

	if($perPage != ARTWORK_PER_PAGE && $perPage != 40 && $perPage != 80){
		$perPage = ARTWORK_PER_PAGE;
	}

	// If we're passed string values that are the same as the defaults,
	// set them to null so that we can have cleaner query strings in the navigation footer
	if($sort == ArtworkSort::CreatedNewest){
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

	if(!$isReviewerView && !$isSubmitterView && !in_array($status, array('all', ArtworkStatus::Approved->value, 'in-use'))){
		$status = ArtworkStatus::Approved->value;
		$filterArtworkStatus = $status;
	}

	if($isReviewerView && !in_array($status, array('all', ArtworkStatus::Unverified->value, ArtworkStatus::Declined->value, ArtworkStatus::Approved->value, 'in-use'))
	                && !in_array($filterArtworkStatus, array('all-admin', ArtworkStatus::Unverified->value, ArtworkStatus::Declined->value, ArtworkStatus::Approved->value, 'in-use'))){
		$status = ArtworkStatus::Approved->value;
		$filterArtworkStatus = $status;
	}

	if($isSubmitterView && !in_array($status, array('all', ArtworkStatus::Unverified->value, ArtworkStatus::Approved->value, 'in-use'))
	                    && !in_array($filterArtworkStatus, array('all-submitter', 'unverified-submitter', ArtworkStatus::Approved->value, 'in-use'))){
		$status = ArtworkStatus::Approved->value;
		$filterArtworkStatus = $status;
	}

	if($queryEbookUrl !== null){
		$artworks = Db::Query('SELECT * from Artworks where EbookUrl = ? and Status = ? limit 1', [$queryEbookUrl, ArtworkStatus::Approved], 'Artwork');
		$totalArtworkCount = sizeof($artworks);
	}
	else{
		$result = Library::FilterArtwork($query, $filterArtworkStatus, $sort, $submitterUserId, $page, $perPage);
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

	$queryString = http_build_query($queryStringParams);

	$pages = ceil($totalArtworkCount / $perPage);
	if($pages > 0 && $page > $pages){
		throw new Exceptions\PageOutOfBoundsException();
	}
}
catch(Exceptions\PageOutOfBoundsException){
	$url = '/artworks?page=' . $pages;
	if($queryString != ''){
		$url .= '&' . $queryString;
	}

	header('Location: ' . $url);
	exit();
}
?><?= Template::Header(['title' => $pageTitle, 'artwork' => true, 'description' => $pageDescription]) ?>
<main class="artworks">
	<section class="narrow">
		<h1>Browse U.S. Public Domain Artwork</h1>
		<p>You can help Standard Ebooks by <a href="/artworks/new">submitting new public domain artwork</a> to add to this catalog for use in future ebooks. For free access to the submission form, <a href="/about#editor-in-chief">contact the Editor-in-Chief</a>.</p>
		<form class="browse-artwork" action="/artworks" method="get" rel="search">
			<label class="select">
				<span>Status</span>
				<span>
					<select name="status" size="1">
						<option value="all"<? if($status === null){ ?> selected="selected"<? } ?>>All</option>
						<? if($isReviewerView || $isSubmitterView){ ?><option value="<?= ArtworkStatus::Unverified->value ?>"<? if($status == ArtworkStatus::Unverified->value){ ?> selected="selected"<? } ?>>Unverified</option><? } ?>
						<? if($isReviewerView){ ?><option value="<?= ArtworkStatus::Declined->value ?>"<? if($status == ArtworkStatus::Declined->value){ ?> selected="selected"<? } ?>>Declined</option><? } ?>
						<option value="<?= ArtworkStatus::Approved->value ?>"<? if($status == ArtworkStatus::Approved->value){ ?> selected="selected"<? } ?>>Approved, not in use</option>
						<option value="in-use"<? if($status == 'in-use'){ ?> selected="selected"<? } ?>>In use</option>
					</select>
				</span>
			</label>
			<label class="search">Keywords
				<input type="search" name="query" value="<?= Formatter::EscapeHtml($query) ?>"/>
			</label>
			<label class="select sort">
				<span>Sort</span>
				<span>
					<select name="sort">
						<option value="<?= ArtworkSort::CreatedNewest->value ?>"<? if($sort == ArtworkSort::CreatedNewest){ ?> selected="selected"<? } ?>>Date added (new &#x2192; old)</option>
						<option value="<?= ArtworkSort::ArtistAlpha->value ?>"<? if($sort == ArtworkSort::ArtistAlpha){ ?> selected="selected"<? } ?>>Artist name (a &#x2192; z)</option>
						<option value="<?= ArtworkSort::CompletedNewest->value ?>"<? if($sort == ArtworkSort::CompletedNewest){ ?> selected="selected"<? } ?>>Date of artwork completion (new &#x2192; old)</option>
					</select>
				</span>
			</label>
			<label class="select">
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
				<a<? if($page > 1){ ?> href="/artworks?page=<?= $page - 1 ?><? if($queryString != ''){ ?><?= Formatter::EscapeXhtmlQueryString('&' . $queryString) ?><? } ?>" rel="prev"<? }else{ ?> aria-disabled="true"<? } ?>>Back</a>
				<ol>
				<? for($i = 1; $i < $pages + 1; $i++){ ?>
					<li<? if($page == $i){ ?> class="highlighted"<? } ?>><a href="/artworks?page=<?= $i ?><? if($queryString != ''){ ?><?= Formatter::EscapeXhtmlQueryString('&' . $queryString) ?><? } ?>"><?= $i ?></a></li>
				<? } ?>
				</ol>
				<a<? if($page < ceil($totalArtworkCount / $perPage)){ ?> href="/artworks?page=<?= $page + 1 ?><? if($queryString != ''){ ?><?= Formatter::EscapeXhtmlQueryString('&' . $queryString) ?><? } ?>" rel="next"<? }else{ ?> aria-disabled="true"<? } ?>>Next</a>
			</nav>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
