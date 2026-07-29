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

	$result = Ebook::GetPlaceholdersPage($page, $perPage);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\PageOutOfBoundsException $ex){
	Template::RedirectToResultsPage($ex->RealPageNumber);
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

		<?= Template::Pagination(result: $result) ?>
	</section>
</main>
<?= Template::Footer() ?>
