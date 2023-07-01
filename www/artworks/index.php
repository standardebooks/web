<?php /** @noinspection PhpUndefinedMethodInspection,PhpIncludeInspection */
require_once('Core.php');

use function Safe\preg_replace;

$page = HttpInput::Int(GET, 'page') ?? 1;
$perPage = HttpInput::Int(GET, 'per-page') ?? COVER_ARTWORK_PER_PAGE;
$query = HttpInput::Str(GET, 'query', false) ?? '';
$status = HttpInput::Str(GET, 'status', false) ?? COVER_ARTWORK_STATUS_ALL;
$sort = HttpInput::Str(GET, 'sort', false);
$pages = 0;
$totalArtworkCount = 0;
$pageDescription = '';
$pageTitle = '';
$queryString = '';

if($page <= 0){
	$page = 1;
}

if($perPage != COVER_ARTWORK_PER_PAGE && $perPage != 100 && $perPage != 200){
	$perPage = COVER_ARTWORK_PER_PAGE;
}

// If we're passed string values that are the same as the defaults,
// set them to null so that we can have cleaner query strings in the navigation footer
if($sort !== null){
	$sort = mb_strtolower($sort);
}

if($sort === 'created-newest'){
	$sort = null;
}

if($status === COVER_ARTWORK_STATUS_ALL){
	$status = null;
}

$artworks = Library::FilterArtwork($query != '' ? $query : null, $status, $sort);
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

if($status != ''){
	$queryString .= '&amp;status=' . urlencode($status);
}

if($sort !== null){
	$queryString .= '&amp;sort=' . urlencode($sort);
}

if($perPage !== COVER_ARTWORK_PER_PAGE){
	$queryString .= '&amp;per-page=' . urlencode((string)$perPage);
}


$queryString = preg_replace('/^&amp;/ius', '', $queryString);
?><?= Template::Header(['title' => $pageTitle, 'description' => $pageDescription]) ?>
<main class="artworks">
	<section class="narrow">
		<hgroup>
			<h1>Browse Artwork</h1>
		</hgroup>

	<?= Template::ArtworkSearchForm(['query' => $query, 'status' => $status, 'sort' => $sort, 'perPage' => $perPage]) ?>

	<? if($totalArtworkCount == 0){ ?>
		<p class="no-results">No artwork matched your filters.  You can try different filters, or <a href="/artworks">browse all artwork</a>.</p>
	<? }else{ ?>
		<?= Template::ArtworkList(['artworks' => $artworks]) ?>
	<? } ?>
	<? if($totalArtworkCount > 0){ ?>
		<nav>
			<a<? if($page > 1){ ?> href="/artworks/?page=<?= $page - 1 ?><? if($queryString != ''){ ?>&amp;<?= $queryString ?><? } ?>" rel="prev"<? }else{ ?> aria-disabled="true"<? } ?>>Back</a>
			<ol>
			<? for($i = 1; $i < $pages + 1; $i++){ ?>
				<li<? if($page == $i){ ?> class="highlighted"<? } ?>><a href="/artworks/?page=<?= $i ?><? if($queryString != ''){ ?>&amp;<?= $queryString ?><? } ?>"><?= $i ?></a></li>
			<? } ?>
			</ol>
			<a<? if($page < ceil($totalArtworkCount / $perPage)){ ?> href="/artworks/?page=<?= $page + 1 ?><? if($queryString != ''){ ?>&amp;<?= $queryString ?><? } ?>" rel="next"<? }else{ ?> aria-disabled="true"<? } ?>>Next</a>
		</nav>
	<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
