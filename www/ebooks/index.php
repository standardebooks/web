<?
require_once('Core.php');

use function Safe\preg_replace;

try{
	$page = HttpInput::GetInt('page', 1);
	$query = HttpInput::GetString('query', false);
	$tag = HttpInput::GetString('tag', false);
	$collection = HttpInput::GetString('collection', false);
	$sort = HttpInput::GetString('sort', false, SORT_NEWEST);
	$pages = 0;
	$totalEbooks = 0;

	if($page <= 0){
		$page = 1;
	}

	if($sort != SORT_AUTHOR_ALPHA && $sort != SORT_NEWEST && $sort != SORT_READING_EASE && $sort != SORT_LENGTH){
		$sort = SORT_NEWEST;
	}

	if($query !== null){
		$ebooks = Library::Search($query);
		$pageTitle = 'Search Standard Ebooks';
		$pageDescription = 'Search results';
		$pageHeader = 'Search Ebooks';
	}
	elseif($tag !== null){
		$tag = strtolower(str_replace('-', ' ', $tag));
		$ebooks = Library::GetEbooksByTag($tag);
		$pageTitle = 'Browse ebooks tagged “' . Formatter::ToPlainText($tag) . '”';
		$pageDescription = 'Page ' . $page . ' of ebooks tagged “' . Formatter::ToPlainText($tag) . '”';
		$pageHeader = 'Ebooks tagged “' . Formatter::ToPlainText($tag) . '”';

		$pages = ceil(sizeof($ebooks) / EBOOKS_PER_PAGE);

		$totalEbooks = sizeof($ebooks);

		$ebooks = array_slice($ebooks, ($page - 1) * EBOOKS_PER_PAGE, EBOOKS_PER_PAGE);
	}
	elseif($collection !== null){
		$ebooks = Library::GetEbooksByCollection($collection);
		$collectionObject = null;
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

			$pageTitle = 'Browse ebooks in the ' . Formatter::ToPlainText($collectionName) . $collectionType;
			$pageDescription = 'A list of ebooks in the ' . Formatter::ToPlainText($collectionName) . ' ' . $collectionType;
			$pageHeader = 'Ebooks in the ' . Formatter::ToPlainText($collectionName) . ' ' . $collectionType;
		}
		else{
			throw new InvalidCollectionException();
		}
	}
	else{
		$pageTitle = 'Browse Standard Ebooks';
		$pageHeader = 'Browse Ebooks';
		$ebooks = Library::GetEbooks($sort);

		$pages = ceil(sizeof($ebooks) / EBOOKS_PER_PAGE);

		$totalEbooks = sizeof($ebooks);

		$ebooks = array_slice($ebooks, ($page - 1) * EBOOKS_PER_PAGE, EBOOKS_PER_PAGE);

		$pageDescription = 'Page ' . $page . ' of the Standard Ebooks ebook library, sorted ';
		switch($sort){
			case SORT_NEWEST:
				$pageDescription .= 'by newest ebooks first.';
				break;
			case SORT_AUTHOR_ALPHA:
				$pageDescription .= 'alphabetically by author name.';
				break;
			case SORT_READING_EASE:
				$pageDescription .= 'by easiest ebooks first.';
				break;
			case SORT_LENGTH:
				$pageDescription .= 'by shortest ebooks first.';
				break;
		}
	}
}
catch(\Exception $ex){
	http_response_code(404);
	include(WEB_ROOT . '/404.php');
	exit();
}
?><?= Template::Header(['title' => $pageTitle, 'highlight' => 'ebooks', 'description' => $pageDescription]) ?>
<main class="ebooks">
	<h1><?= $pageHeader ?></h1>
	<?= Template::SearchForm(['query' => $query]) ?>
	<? if(sizeof($ebooks) == 0){ ?>
		<p class="no-results">No ebooks matched your search.  You can try different search terms, or <a href="/ebooks">browse all of our ebooks</a>.</p>
	<? }else{ ?>
		<?= Template::EbookGrid(['ebooks' => $ebooks]) ?>
	<? } ?>
	<? if(sizeof($ebooks) > 0 && $query === null && $tag === null && $collection === null){ ?>
		<nav>
			<a<? if($page > 1){ ?> href="/ebooks/<? if($page - 1 > 1){ ?>?page=<?= $page - 1 ?><? } ?><? if($sort != SORT_NEWEST){ ?><? if($page - 1 <= 1){ ?>?<? }else{ ?>&amp;<? } ?>sort=<?= $sort ?><? } ?>" rel="previous"<? }else{ ?> aria-disabled="true"<? } ?>>Back</a>
			<ol>
			<? for($i = 1; $i < $pages + 1; $i++){ ?>
				<li<? if($page == $i){ ?> class="highlighted"<? } ?>><a href="/ebooks/<? if($i - 1 >= 1){ ?>?page=<?= $i ?><? } ?><? if($sort != SORT_NEWEST){ ?><? if($i - 1 < 1){ ?>?<? }else{ ?>&amp;<? } ?>sort=<?= $sort ?><? } ?>"><?= $i ?></a></li>
			<? } ?>
			</ol>
			<a<? if($page < ceil($totalEbooks / EBOOKS_PER_PAGE)){ ?> href="/ebooks/?page=<?= $page + 1 ?><? if($sort != SORT_NEWEST){ ?>&amp;sort=<?= $sort ?><? } ?>" rel="next"<? }else{ ?> aria-disabled="true"<? } ?>>Next</a>
		</nav>
	<? }elseif(sizeof($ebooks) > 0 && $tag !== null){ ?>
		<nav>
			<a<? if($page > 1){ ?> href="/tags/<?= Formatter::ToPlainText(str_replace(' ', '-', $tag)) ?>/<? if($page - 1 > 1){ ?>?page=<?= $page - 1 ?><? } ?>" rel="previous"<? }else{ ?> aria-disabled="true"<? } ?>>Back</a>
			<ol>
			<? for($i = 1; $i < $pages + 1; $i++){ ?>
				<li<? if($page == $i){ ?> class="highlighted"<? } ?>><a href="/tags/<?= Formatter::ToPlainText(str_replace(' ', '-', $tag)) ?>/<? if($i - 1 >= 1){ ?>?page=<?= $i ?><? } ?>"><?= $i ?></a></li>
			<? } ?>
			</ol>
			<a<? if($page < ceil($totalEbooks / EBOOKS_PER_PAGE)){ ?> href="/tags/<?= Formatter::ToPlainText(str_replace(' ', '-', $tag)) ?>/?page=<?= $page + 1 ?>" rel="next"<? }else{ ?> aria-disabled="true"<? } ?>>Next</a>
		</nav>
	<? } ?>
	<? if(sizeof($ebooks) > 0 && $query === null && $tag === null && $collection === null){ ?>
		<aside class="sort">
			<form action="/ebooks" method="get">
				<label>Sort by
					<select name="sort">
						<option value="<?= SORT_NEWEST ?>"<? if($sort == SORT_NEWEST){ ?> selected<? } ?>>newest</option>
						<option value="<?= SORT_AUTHOR_ALPHA ?>"<? if($sort == SORT_AUTHOR_ALPHA){ ?> selected<? } ?>>author name</option>
						<option value="<?= SORT_READING_EASE ?>"<? if($sort == SORT_READING_EASE){ ?> selected<? } ?>>reading ease</option>
						<option value="<?= SORT_LENGTH ?>"<? if($sort == SORT_LENGTH){ ?> selected<? } ?>>length</option>
					</select>
				</label>
				<button>Sort</button>
			</form>
		</aside>
		<? if($page == 1){ ?>
		<?= Template::ContributeAlert() ?>
		<? } ?>
	<? } ?>
</main>
<?= Template::Footer() ?>
