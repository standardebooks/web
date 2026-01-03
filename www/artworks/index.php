<?
$artworks = [];
$page = HttpInput::Int(GET, 'page') ?? 1;
$perPage = HttpInput::Int(GET, 'per-page') ?? ARTWORK_PER_PAGE;
$query = HttpInput::Str(GET, 'query');
$queryEbookUrl = HttpInput::Str(GET, 'query-ebook-url');
$artworkFilterType = Enums\ArtworkFilterType::tryFrom(HttpInput::Str(GET, 'status') ?? '');
$sort = Enums\ArtworkSortType::tryFrom(HttpInput::Str(GET, 'sort') ?? '');
$pages = 0;
$totalArtworkCount = 0;
$pageDescription = '';
$pageTitle = '';
$queryString = '';
$isReviewerView = Session::$User?->Benefits->CanReviewArtwork ?? false;
$submitterUserId = Session::$User?->Benefits->CanUploadArtwork ? Session::$User->UserId : null;
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
		if($artworkFilterType == Enums\ArtworkFilterType::All || $artworkFilterType === null){
			$artworkFilterType = Enums\ArtworkFilterType::Admin;
		}
		if($artworkFilterType == Enums\ArtworkFilterType::UnverifiedSubmitter){
			$artworkFilterType = Enums\ArtworkFilterType::Unverified;
		}
	}

	if($isSubmitterView){
		if($artworkFilterType == Enums\ArtworkFilterType::All || $artworkFilterType === null){
			$artworkFilterType = Enums\ArtworkFilterType::ApprovedSubmitter;
		}
		if($artworkFilterType == Enums\ArtworkFilterType::Unverified){
			$artworkFilterType = Enums\ArtworkFilterType::UnverifiedSubmitter;
		}
	}

	if(
		!$isReviewerView
		&&
		!$isSubmitterView
		&&
		!in_array($artworkFilterType, [Enums\ArtworkFilterType::Approved, Enums\ArtworkFilterType::ApprovedNotInUse, Enums\ArtworkFilterType::ApprovedInUse])
	){
		$artworkFilterType = Enums\ArtworkFilterType::Approved;
	}

	if(
		$isSubmitterView
		&&
		!in_array($artworkFilterType, [Enums\ArtworkFilterType::ApprovedSubmitter, Enums\ArtworkFilterType::UnverifiedSubmitter, Enums\ArtworkFilterType::ApprovedInUse, Enums\ArtworkFilterType::ApprovedNotInUse])
	){
		$artworkFilterType = Enums\ArtworkFilterType::ApprovedSubmitter;
	}

	if($queryEbookUrl !== null){
		// We're being called from the `review` script, and we're only interested if the artwork exists for this URL.
		$artworks[] = Db::Query('SELECT a.* from Artworks a inner join Ebooks e using (EbookId) where e.Identifier = ? and Status = ? limit 1', [$queryEbookUrl, Enums\ArtworkStatusType::Approved], Artwork::class)[0] ?? throw new Exceptions\ArtworkNotFoundException();
		$totalArtworkCount = 1;
	}
	else{
		$result = Artwork::GetAllByFilter($query, $artworkFilterType, $sort, $submitterUserId, $page, $perPage);
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

	if($artworkFilterType !== null){
		$queryStringParams['status'] = $artworkFilterType->value;
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
		$canonicalUrl .= '?' . $queryString;
	}

	$pages = ceil($totalArtworkCount / $perPage);
	if($pages > 0 && $page > $pages){
		throw new Exceptions\PageOutOfBoundsException();
	}
}
catch(Exceptions\ArtworkNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\PageOutOfBoundsException){
	/** @var string $queryStringWithoutPage */
	$url = '/artworks?page=' . $pages;
	if($queryStringWithoutPage != ''){
		$url .= '&' . $queryStringWithoutPage;
	}

	header('Location: ' . $url);
	exit();
}
?><?= Template::Header(title: $pageTitle, css: ['/css/artwork.css'], description: $pageDescription, canonicalUrl: $canonicalUrl) ?>
<main class="artworks">
	<section class="narrow">
		<h1>Browse U.S. Public Domain Artwork</h1>
		<? if(Session::$User?->Benefits->CanUploadArtwork){ ?>
			<ul role="menu">
				<li><a href="/artworks/new">Submit an artwork</a></li>
			</ul>
		<? }else{ ?>
			<p>You can help Standard Ebooks by <a href="/artworks/new">submitting new public domain artwork</a> to add to this catalog for use in future ebooks. For free access to the submission form, <a href="/about#editor-in-chief">contact the Editor-in-Chief</a>.</p>
		<? } ?>
		<form class="browse-artwork" action="/artworks" method="<?= Enums\HttpMethod::Get->value ?>" rel="search" role="search">
			<label>
				<span>Status</span>
				<select name="status">
					<option value="<?= Enums\ArtworkFilterType::All->value ?>"<? if($artworkFilterType === null || $artworkFilterType == Enums\ArtworkFilterType::All){ ?> selected="selected"<? } ?>>All</option>
					<? if($isReviewerView){ ?>
						<option value="<?= Enums\ArtworkFilterType::Unverified->value ?>"<? if($artworkFilterType == Enums\ArtworkFilterType::Unverified){ ?> selected="selected"<? } ?>>Unverified</option>
					<? } ?>
					<? if($isSubmitterView){ ?>
						<option value="<?= Enums\ArtworkFilterType::UnverifiedSubmitter->value ?>"<? if($artworkFilterType == Enums\ArtworkFilterType::UnverifiedSubmitter){ ?> selected="selected"<? } ?>>Unverified</option>
					<? } ?>
					<? if($isReviewerView){ ?>
						<option value="<?= Enums\ArtworkFilterType::Declined->value ?>"<? if($artworkFilterType == Enums\ArtworkFilterType::Declined){ ?> selected="selected"<? } ?>>Declined</option>
					<? } ?>
					<option value="<?= Enums\ArtworkFilterType::ApprovedNotInUse->value ?>"<? if($artworkFilterType == Enums\ArtworkFilterType::ApprovedNotInUse){ ?> selected="selected"<? } ?>>Approved, not in use</option>
					<option value="<?= Enums\ArtworkFilterType::ApprovedInUse->value ?>"<? if($artworkFilterType == Enums\ArtworkFilterType::ApprovedInUse){ ?> selected="selected"<? } ?>>Approved, in use</option>
				</select>
			</label>
			<label>
				<span>Keywords</span>
				<input type="search" name="query" value="<?= Formatter::EscapeHtml($query) ?>"/>
			</label>
			<label class="sort">
				<span>Sort</span>
				<select name="sort">
					<option value="<?= Enums\ArtworkSortType::CreatedNewest->value ?>"<? if($sort == Enums\ArtworkSortType::CreatedNewest){ ?> selected="selected"<? } ?>>Date added (new → old)</option>
					<option value="<?= Enums\ArtworkSortType::ArtistAlpha->value ?>"<? if($sort == Enums\ArtworkSortType::ArtistAlpha){ ?> selected="selected"<? } ?>>Artist name (a → z)</option>
					<option value="<?= Enums\ArtworkSortType::CompletedNewest->value ?>"<? if($sort == Enums\ArtworkSortType::CompletedNewest){ ?> selected="selected"<? } ?>>Date of artwork completion (new → old)</option>
				</select>
			</label>
			<label>
				<span>Per page</span>
				<select name="per-page">
					<option value="20"<? if($perPage == 20){ ?> selected="selected"<? } ?>>20</option>
					<option value="40"<? if($perPage == 40){ ?> selected="selected"<? } ?>>40</option>
					<option value="80"<? if($perPage == 80){ ?> selected="selected"<? } ?>>80</option>
				</select>
			</label>
			<button>Filter</button>
		</form>

		<?= Template::ImageCopyrightNotice() ?>

		<? if($totalArtworkCount == 0){ ?>
			<p class="no-results">No artwork matched your filters. You can try different filters, or <a href="/artworks">browse all artwork</a>.</p>
		<? }else{ ?>
			<?= Template::ArtworkList(artworks: $artworks) ?>
		<? } ?>

		<? if($totalArtworkCount > 0){ ?>
			<nav class="pagination" aria-label="Pagination">
				<a<? if($page > 1){ ?> href="/artworks?page=<?= $page - 1 ?><? if($queryStringWithoutPage != ''){ ?>&amp;<?= Formatter::EscapeHtml($queryStringWithoutPage) ?><? } ?>" rel="prev"<? }else{ ?> aria-disabled="true"<? } ?>>Back</a>
				<ol>
				<? for($i = 1; $i < $pages + 1; $i++){ ?>
					<li><a <? if($page == $i){ ?>aria-current="page" href="#"<? }else{ ?>href="/artworks?page=<?= $i ?><? if($queryStringWithoutPage != ''){ ?>&amp;<?= Formatter::EscapeHtml($queryStringWithoutPage) ?><? } ?>"<? } ?>><?= $i ?></a></li>
				<? } ?>
				</ol>
				<a<? if($page < ceil($totalArtworkCount / $perPage)){ ?> href="/artworks?page=<?= $page + 1 ?><? if($queryStringWithoutPage != ''){ ?>&amp;<?= Formatter::EscapeHtml($queryStringWithoutPage) ?><? } ?>" rel="next"<? }else{ ?> aria-disabled="true"<? } ?>>Next</a>
			</nav>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
