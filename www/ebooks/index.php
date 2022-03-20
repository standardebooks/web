<?
require_once('Core.php');

use function Safe\preg_replace;

try{
	$page = HttpInput::Int(GET, 'page', 1);
	$perPage = HttpInput::Int(GET, 'per-page', EBOOKS_PER_PAGE);
	$query = HttpInput::Str(GET, 'query', false);
	$tags = HttpInput::GetArray('tags', []);
	$collection = HttpInput::Str(GET, 'collection', false);
	$view = HttpInput::Str(GET, 'view', false);
	$sort = HttpInput::Str(GET, 'sort', false);
	$pages = 0;
	$totalEbooks = 0;
	$collectionObject = null;

	if($page <= 0){
		$page = 1;
	}

	if($perPage != EBOOKS_PER_PAGE && $perPage != 24 && $perPage != 48){
		$perPage = EBOOKS_PER_PAGE;
	}

	// If we're passed string values that are the same as the defaults,
	// set them to null so that we can have cleaner query strings in the navigation footer
	if($view !== null){
		$view = mb_strtolower($view);
	}

	if($sort !== null){
		$sort = mb_strtolower($sort);
	}

	if($view === 'grid'){
		$view = null;
	}

	if($sort === 'newest'){
		$sort = null;
	}

	if($query === ''){
		$query = null;
	}

	if(sizeof($tags) == 1 && mb_strtolower($tags[0]) == 'all'){
		$tags = [];
	}

	// Replace dashes passed in from URLs like /tags/science-fiction
	foreach($tags as $key => $tag){
		$tags[$key] = str_replace('-', ' ', $tag);
	}

	// Are we looking at a collection?
	if($collection !== null){
		$ebooks = Library::GetEbooksByCollection($collection);

		// Get the *actual* name of the collection, in case there are accent marks (like "Arsène Lupin")
		if(sizeof($ebooks) > 0){
			foreach($ebooks[0]->Collections as $c){
				if($collection == Formatter::MakeUrlSafe($c->Name)){
					$collectionObject = $c;
				}
			}
		}
		if($collectionObject !== null){
			$collectionName = preg_replace('/^The /ius', '', $collectionObject->Name) ?? '';
			$collectionType = $collectionObject->Type ?? 'collection';

			# This is a kind of .endswith() test
			if(substr_compare(mb_strtolower($collectionObject->Name), mb_strtolower($collectionObject->Type), -strlen(mb_strtolower($collectionObject->Type))) !== 0){
				$collectionType = ' ' . $collectionType;
			}
			else{
				$collectionType = '';
			}

			$pageTitle = 'Browse free ebooks in the ' . Formatter::ToPlainText($collectionName) . $collectionType;
			$pageDescription = 'A list of free ebooks in the ' . Formatter::ToPlainText($collectionName) . ' ' . $collectionType;
			$pageHeader = 'Free ebooks in the ' . Formatter::ToPlainText($collectionName) . ' ' . $collectionType;
		}
		else{
			throw new Exceptions\InvalidCollectionException();
		}
	}
	else{
		$ebooks = Library::FilterEbooks($query, $tags, $sort);
		$pageTitle = 'Browse Standard Ebooks';
		$pageHeader = 'Browse Ebooks';
		$pages = ceil(sizeof($ebooks) / $perPage);
		$totalEbooks = sizeof($ebooks);
		$ebooks = array_slice($ebooks, ($page - 1) * $perPage, $perPage);
	}


	if($page > 1){
		$pageTitle .= ', page ' . $page;
	}
	$pageDescription = 'Page ' . $page . ' of the Standard Ebooks free ebook library';

	$queryString = '';

	if($collection === null){
		if($query != ''){
			$queryString .= '&amp;query=' . urlencode($query);
		}

		foreach($tags as $tag){
			$queryString .= '&amp;tags[]=' . urlencode($tag);
		}

		if($view !== null){
			$queryString .= '&amp;view=' . urlencode($view);
		}

		if($sort !== null){
			$queryString .= '&amp;sort=' . urlencode($sort);
		}

		if($perPage !== EBOOKS_PER_PAGE){
			$queryString .= '&amp;per-page=' . urlencode((string)$perPage);
		}
	}

	$queryString = preg_replace('/^&amp;/ius', '', $queryString);
}
catch(Exceptions\InvalidCollectionException $ex){
	http_response_code(404);
	include(WEB_ROOT . '/404.php');
	exit();
}
?><?= Template::Header(['title' => $pageTitle, 'highlight' => 'ebooks', 'description' => $pageDescription]) ?>
<main class="ebooks">
	<h1><?= $pageHeader ?></h1>
	<? if(DONATION_DRIVE_ON){ ?>
	<?= Template::DonationProgress() ?>
	<? }elseif(DONATION_HOLIDAY_ALERT_ON){ ?>
	<?= Template::DonationAlert() ?>
	<? } ?>
	<? if($collection === null){ ?>
	<?= Template::SearchForm(['query' => $query, 'tags' => $tags, 'sort' => $sort, 'view' => $view, 'perPage' => $perPage]) ?>
	<? } ?>
	<? if(sizeof($ebooks) == 0){ ?>
		<p class="no-results">No ebooks matched your filters.  You can try different filters, or <a href="/ebooks">browse all of our ebooks</a>.</p>
	<? }else{ ?>
		<?= Template::EbookGrid(['ebooks' => $ebooks, 'view' => $view, 'collection' => $collectionObject]) ?>
	<? } ?>
	<? if(sizeof($ebooks) > 0 && $collection === null){ ?>
		<nav>
			<a<? if($page > 1){ ?> href="/ebooks/?page=<?= $page - 1 ?><? if($queryString != ''){ ?>&amp;<?= $queryString ?><? } ?>" rel="previous"<? }else{ ?> aria-disabled="true"<? } ?>>Back</a>
			<ol>
			<? for($i = 1; $i < $pages + 1; $i++){ ?>
				<li<? if($page == $i){ ?> class="highlighted"<? } ?>><a href="/ebooks/?page=<?= $i ?><? if($queryString != ''){ ?>&amp;<?= $queryString ?><? } ?>"><?= $i ?></a></li>
			<? } ?>
			</ol>
			<a<? if($page < ceil($totalEbooks / $perPage)){ ?> href="/ebooks/?page=<?= $page + 1 ?><? if($queryString != ''){ ?>&amp;<?= $queryString ?><? } ?>" rel="next"<? }else{ ?> aria-disabled="true"<? } ?>>Next</a>
		</nav>
	<? } ?>
	<? if(sizeof($ebooks) > 0 && $query === null && sizeof($tags) == 0 && $collection === null && $page == 1){ ?>
		<?= Template::ContributeAlert() ?>
	<? } ?>
</main>
<?= Template::Footer() ?>
