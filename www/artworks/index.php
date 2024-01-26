<?
$page = HttpInput::Int(GET, 'page') ?? 1;
$perPage = HttpInput::Int(GET, 'per-page') ?? ARTWORK_PER_PAGE;
$query = HttpInput::Str(GET, 'query') ?? '';
$queryEbookUrl = HttpInput::Str(GET, 'query-ebook-url');
$status = HttpInput::Str(GET, 'status') ?? null;
$filterArtworkStatus = $status;
$sort = HttpInput::Str(GET, 'sort');
$pages = 0;
$totalArtworkCount = 0;
$pageDescription = '';
$pageTitle = '';
$queryString = '';
$isReviewerView = $GLOBALS['User']?->Benefits?->CanReviewArtwork ?? false;
$submitterUserId = $GLOBALS['User']?->Benefits?->CanUploadArtwork ? $GLOBALS['User']->UserId : null;
$isSubmitterView = !$isReviewerView && $submitterUserId !== null;

if($page <= 0){
	$page = 1;
}

if($perPage != ARTWORK_PER_PAGE && $perPage != 40 && $perPage != 80){
	$perPage = ARTWORK_PER_PAGE;
}

// If we're passed string values that are the same as the defaults,
// set them to null so that we can have cleaner query strings in the navigation footer
if($sort !== null){
	$sort = mb_strtolower($sort);
}

if($sort == 'created-newest'){
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
	$artworks = Db::Query('SELECT * from Artworks where EbookUrl = ? and Status = ?', [$queryEbookUrl, ArtworkStatus::Approved], 'Artwork');
}
else{
	$artworks = Library::FilterArtwork($query != '' ? $query : null, $filterArtworkStatus, $sort, $submitterUserId);
}

$pageTitle = 'Browse Artwork';
$pages = ceil(sizeof($artworks) / $perPage);
$totalArtworkCount = sizeof($artworks);
$artworks = array_slice($artworks, ($page - 1) * $perPage, $perPage);

if($page > 1){
	$pageTitle .= ', page ' . $page;
}

$pageDescription = 'Page ' . $page . ' of artwork';

if($query != ''){
	$queryString .= '&amp;query=' . urlencode($query);
}

if($status !== null){
	$queryString .= '&amp;status=' . urlencode($status);
}

if($sort !== null){
	$queryString .= '&amp;sort=' . urlencode($sort);
}

if($perPage !== ARTWORK_PER_PAGE){
	$queryString .= '&amp;per-page=' . urlencode((string)$perPage);
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
						<option value="<?= SORT_COVER_ARTWORK_CREATED_NEWEST ?>"<? if($sort == SORT_COVER_ARTWORK_CREATED_NEWEST){ ?> selected="selected"<? } ?>>Date added (new &#x2192; old)</option>
						<option value="<?= SORT_COVER_ARTIST_ALPHA ?>"<? if($sort == SORT_COVER_ARTIST_ALPHA){ ?> selected="selected"<? } ?>>Artist name (a &#x2192; z)</option>
						<option value="<?= SORT_COVER_ARTWORK_COMPLETED_NEWEST ?>"<? if($sort == SORT_COVER_ARTWORK_COMPLETED_NEWEST){ ?> selected="selected"<? } ?>>Date of artwork completion (new &#x2192; old)</option>
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
				<a<? if($page > 1){ ?> href="/artworks?page=<?= $page - 1 ?><? if($queryString != ''){ ?><?= $queryString ?><? } ?>" rel="prev"<? }else{ ?> aria-disabled="true"<? } ?>>Back</a>
				<ol>
				<? for($i = 1; $i < $pages + 1; $i++){ ?>
					<li<? if($page == $i){ ?> class="highlighted"<? } ?>><a href="/artworks?page=<?= $i ?><? if($queryString != ''){ ?><?= $queryString ?><? } ?>"><?= $i ?></a></li>
				<? } ?>
				</ol>
				<a<? if($page < ceil($totalArtworkCount / $perPage)){ ?> href="/artworks?page=<?= $page + 1 ?><? if($queryString != ''){ ?><?= $queryString ?><? } ?>" rel="next"<? }else{ ?> aria-disabled="true"<? } ?>>Next</a>
			</nav>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
