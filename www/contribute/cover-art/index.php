<?
require_once('Core.php');

use function Safe\preg_replace;

$page = HttpInput::Int(GET, 'page') ?? 1;
$perPage = HttpInput::Int(GET, 'per-page') ?? COVER_ART_PER_PAGE;
$query = HttpInput::Str(GET, 'query', false) ?? '';
$status = HttpInput::Str(GET, 'status', false) ?? COVER_ART_STATUS_ALL;
$sort = HttpInput::Str(GET, 'sort', false);
$pages = 0;
$totalCoverArtCount = 0;
$pageDescription = '';
$pageTitle = '';
$queryString = '';
$feedUrl = null;
$feedTitle  = '';

if($page <= 0){
	$page = 1;
}

if($perPage != COVER_ART_PER_PAGE && $perPage != 96 && $perPage != 192){
	$perPage = COVER_ART_PER_PAGE;
}

// If we're passed string values that are the same as the defaults,
// set them to null so that we can have cleaner query strings in the navigation footer
if($sort !== null){
	$sort = mb_strtolower($sort);
}

if($sort === 'newest'){
	$sort = null;
}

if($status === COVER_ART_STATUS_ALL){
	$status = null;
}

$coverArtList = Library::FilterCoverArt($query != '' ? $query : null, $status, $sort);
$pageTitle = 'Browse Standard Ebooks Cover Art';
$pages = ceil(sizeof($coverArtList) / $perPage);
$totalCoverArtCount = sizeof($coverArtList);
$coverArtList = array_slice($coverArtList, ($page - 1) * $perPage, $perPage);

if($page > 1){
	$pageTitle .= ', page ' . $page;
}

$pageDescription = 'Page ' . $page . ' of the Standard Ebooks cover art collection';

if($query != ''){
	$queryString .= '&amp;query=' . urlencode($query);
}

if($status != ''){
	$queryString .= '&amp;status=' . urlencode($status);
}

if($sort !== null){
	$queryString .= '&amp;sort=' . urlencode($sort);
}

if($perPage !== COVER_ART_PER_PAGE){
	$queryString .= '&amp;per-page=' . urlencode((string)$perPage);
}


$queryString = preg_replace('/^&amp;/ius', '', $queryString);
?><?= Template::Header(['title' => $pageTitle, 'feedUrl' => $feedUrl, 'feedTitle' => $feedTitle, 'description' => $pageDescription]) ?>
<main class="cover-art">
	<h1>Browse Cover Art Collection</h1>

	<?= Template::CoverArtSearchForm(['query' => $query, 'status' => $status, 'sort' => $sort, 'perPage' => $perPage]) ?>

	<? if(sizeof($coverArtList) == 0){ ?>
		<p class="no-results">No cover art matched your filters.  You can try different filters, or <a href="/contribute/cover-art">browse all cover art</a>.</p>
	<? }else{ ?>
		<?= Template::CoverArtGrid(['coverArtList' => $coverArtList]) ?>
	<? } ?>
	<? if(sizeof($coverArtList) > 0){ ?>
		<nav>
			<a<? if($page > 1){ ?> href="/contribute/cover-art/?page=<?= $page - 1 ?><? if($queryString != ''){ ?>&amp;<?= $queryString ?><? } ?>" rel="prev"<? }else{ ?> aria-disabled="true"<? } ?>>Back</a>
			<ol>
			<? for($i = 1; $i < $pages + 1; $i++){ ?>
				<li<? if($page == $i){ ?> class="highlighted"<? } ?>><a href="/contribute/cover-art/?page=<?= $i ?><? if($queryString != ''){ ?>&amp;<?= $queryString ?><? } ?>"><?= $i ?></a></li>
			<? } ?>
			</ol>
			<a<? if($page < ceil($totalCoverArtCount / $perPage)){ ?> href="/contribute/cover-art/?page=<?= $page + 1 ?><? if($queryString != ''){ ?>&amp;<?= $queryString ?><? } ?>" rel="next"<? }else{ ?> aria-disabled="true"<? } ?>>Next</a>
		</nav>
	<? } ?>

</main>
<?= Template::Footer() ?>
