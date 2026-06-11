<?
/**
 * GET		/ebook-placeholders
 */

try{
	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditEbookPlaceholders){
		throw new Exceptions\PermissionsInvalidException();
	}

	$page = Http::$Request->QueryString->Get('page', 'int') ?? null;
	$perPage = 50;

	if($page <= 0){
		$page = 1;
	}

	$result = Ebook::GetAllPlaceholders($page, $perPage);

	$ebooks = $result['ebookPlaceholders'];
	$totalEbooks = $result['count'];

	$pages = (int)ceil($totalEbooks / $perPage);

	if($pages > 0 && $page > $pages){
		throw new Exceptions\PageOutOfBoundsException();
	}

}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\PageOutOfBoundsException){
	header('location: /ebook-placeholders?page=' . $pages);
	exit();
}
?>
<?= Template::Header(
	title: 'Ebook Placeholders',
	css: ['/css/ebook-placeholder.css'],
	description: 'Manage ebook placeholders in the Standard Ebooks system.'
) ?>
<main>
	<section class="narrow">
		<h1>Ebook Placeholders</h1>

		<ul role="menu">
			<li><a href="/ebook-placeholders/new">Create an ebook placeholder</a></li>
		</ul>

		<? if(sizeof($ebooks) == 0){ ?>
			<p class="empty-notice">None.</p>
		<? }else{ ?>
			<ol class="ebook-placeholders">
				<? foreach($ebooks as $ebook){ ?>
					<li>
						<p>
							<a href="<?= $ebook->Url ?>"><i><?= Formatter::EscapeHtml($ebook->Title) ?></i></a>
							by <a href="<?= $ebook->AuthorsUrl ?>"><?= Formatter::EscapeHtml($ebook->AuthorsString) ?></a> • <a href="<?= $ebook->EditUrl ?>">Edit</a>
						</p>
					</li>
				<? } ?>
			</ol>
		<? } ?>

		<? if(sizeof($ebooks) > 0){ ?>
			<nav class="pagination" aria-label="Pagination">
				<a<? if($page > 1){ ?> href="/ebook-placeholders?page=<?= $page - 1 ?>" rel="prev"<? }else{ ?> aria-disabled="true"<? } ?>>Back</a>
				<ol>
					<? for($i = 1; $i < $pages + 1; $i++){ ?>
						<li>
							<a <? if($page == $i){ ?>aria-current="page" href="#"<? }else{ ?>href="/ebook-placeholders?page=<?= $i ?>"<? } ?>><?= $i ?></a>
						</li>
					<? } ?>
				</ol>
				<a<? if($page < $pages){ ?> href="/ebook-placeholders?page=<?= $page + 1 ?>" rel="next"<? }else{ ?> aria-disabled="true"<? } ?>>Next</a>
			</nav>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
