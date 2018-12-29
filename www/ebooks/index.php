<?
require_once('Core.php');

try{
	$page = HttpInput::GetInt('page') ?? 1;
	$query = HttpInput::GetString('query', false);
	$sort = HttpInput::GetString('sort', false) ?? SORT_NEWEST;

	if($page <= 0){
		$page = 1;
	}

	if($sort != SORT_AUTHOR_ALPHA && $sort != SORT_NEWEST){
		$sort = SORT_NEWEST;
	}

	if($query !== null){
		$ebooks = Library::Search($query);
		$pageDescription = 'Search results';
	}
	else{
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
		}
	}

	$pageTitle = 'Browse Standard Ebooks';
	if($query !== null){
		$pageTitle = 'Search Standard Ebooks';
	}
}
catch(\Exception $ex){
	http_response_code(404);
	include(SITE_ROOT . '/www/404.php');
	exit();
}
?><?= Template::Header(['title' => $pageTitle, 'highlight' => 'ebooks', 'description' => $pageDescription]) ?>
<main class="ebooks">
		<h1><? if($query === null){ ?>Browse<? }else{ ?>Search<? } ?> Ebooks</h1>
		<?= Template::SearchForm(['query' => $query]) ?>
		<? if(sizeof($ebooks) == 0){ ?>
			<p class="no-results">No ebooks matched your search.  You can try different search terms, or <a href="/ebooks/">browse all of our ebooks</a>.</p>
		<? }else{ ?>
			<?= Template::EbookGrid(['ebooks' => $ebooks]) ?>
		<? } ?>
		<? if($query === null){ ?>
			<nav>
				<a<? if($page > 1){ ?> href="/ebooks/<? if($page - 1 > 1){ ?>?page=<?= $page - 1 ?><? } ?><? if($sort != SORT_NEWEST){ ?><? if($page - 1 <= 1){ ?>?<? }else{ ?>&amp;<? } ?>sort=<?= $sort ?><? } ?>"<? } ?> rel="previous">Back</a>
				<ol>
				<? for($i = 1; $i < $pages + 1; $i++){ ?>
					<li<? if($page == $i){ ?> class="highlighted"<? } ?>><a href="/ebooks/<? if($i - 1 >= 1){ ?>?page=<?= $i ?><? } ?><? if($sort != SORT_NEWEST){ ?><? if($i - 1 < 1){ ?>?<? }else{ ?>&amp;<? } ?>sort=<?= $sort ?><? } ?>"><?= $i ?></a></li>
				<? } ?>
				</ol>
				<a<? if($page < ceil($totalEbooks / EBOOKS_PER_PAGE)){ ?> href="/ebooks/?page=<?= $page + 1 ?><? if($sort != SORT_NEWEST){ ?>&amp;sort=<?= $sort ?><? } ?>"<? }else{ ?> class="disabled"<? } ?> rel="next">Next</a>
			</nav>
			<aside class="sort">
				<p>Sort by</p>
				<a class="button<? if($sort == SORT_NEWEST){ ?> check<? } ?>" href="/ebooks/">newest</a>
				<a class="button<? if($sort == SORT_AUTHOR_ALPHA){ ?> check<? } ?>" href="/ebooks/?sort=<?= SORT_AUTHOR_ALPHA ?>">alpha by author</a>
			</aside>
			<? if($page == 1){ ?>
			<?= Template::ContributeAlert() ?>
			<? } ?>
		<? } ?>
</main>
<?= Template::Footer() ?>
