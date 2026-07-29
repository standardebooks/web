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

	$page = Http::$Request->QueryString->Get('page', 'int') ?? 1;
	$perPage = 50;

	$result = Ebook::GetAllPlaceholdersByPage($page, $perPage);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\PageOutOfBoundsException $ex){
	header('location: /ebook-placeholders?page=' . $ex->RealPageNumber);
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

		<? if(sizeof($result->Results) == 0){ ?>
			<p class="empty-notice">None.</p>
		<? }else{ ?>
			<ol class="ebook-placeholders">
				<? foreach($result->Results as $ebook){ ?>
					<li>
						<p>
							<a href="<?= $ebook->Url ?>"><i><?= Formatter::EscapeHtml($ebook->Title) ?></i></a>
							by <a href="<?= $ebook->AuthorsUrl ?>"><?= Formatter::EscapeHtml($ebook->AuthorsString) ?></a> • <a href="<?= $ebook->EditUrl ?>">Edit</a>
						</p>
					</li>
				<? } ?>
			</ol>
		<? } ?>

		<? if(sizeof($result->Results) > 0){ ?>
			<nav class="pagination" aria-label="Pagination">
				<a<? if($result->Page > 1){ ?> href="/ebook-placeholders?page=<?= $result->Page - 1 ?>" rel="prev"<? }else{ ?> aria-disabled="true"<? } ?>>Back</a>
				<ol>
					<? for($i = 1; $i < $result->TotalPages + 1; $i++){ ?>
						<li>
							<a <? if($result->Page == $i){ ?>aria-current="page" href="#"<? }else{ ?>href="/ebook-placeholders?page=<?= $i ?>"<? } ?>><?= $i ?></a>
						</li>
					<? } ?>
				</ol>
				<a<? if($result->Page < $result->TotalPages){ ?> href="/ebook-placeholders?page=<?= $result->Page + 1 ?>" rel="next"<? }else{ ?> aria-disabled="true"<? } ?>>Next</a>
			</nav>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
