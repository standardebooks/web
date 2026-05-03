<?
/**
 * GET		/ebooks/:url-path/delete
 */

try{
	/** @var non-falsy-string $urlPath Contains the portion of the URL (without query string) that comes after `https://standardebooks.org/ebooks/`. */
	$urlPath = EBOOKS_IDENTIFIER_PREFIX . trim(str_replace('.', '', HttpInput::Str(GET, 'url-path') ?? ''), '/');

	$ebook = Ebook::GetByIdentifier($urlPath);

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(
		!Session::$User->Benefits->CanEditEbookPlaceholders
		||
		!$ebook->IsPlaceholder()
		||
		$ebook->EbookPlaceholder === null
	){
		throw new Exceptions\InvalidPermissionsException();
	}
}
catch(Exceptions\EbookNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
?>
<?= Template::Header(
	title: 'Delete ' . $ebook->Title,
	css: ['/css/ebook-placeholder.css'],
	description: 'Delete ' . $ebook->Title
) ?>
<main>
	<section class="narrow">
		<nav class="breadcrumbs" aria-label="Breadcrumbs">
			<a href="<?= $ebook->AuthorsUrl ?>"><?= $ebook->AuthorsString ?></a> →
			<a href="<?= $ebook->Url ?>"><?= Formatter::EscapeHtml($ebook->Title) ?></a> →
		</nav>
		<h1>Delete</h1>

		<form method="<?= Enums\HttpMethod::Post->value ?>" action="<?= $ebook->Url ?>">
			<input type="hidden" name="_method" value="<?= Enums\HttpMethod::Delete->value ?>" />
			<p>Are you sure you want to permanently delete <i><?= Formatter::EscapeHtml($ebook->Title) ?></i>?</p>
			<div class="footer">
				<button class="delete">Delete</button>
			</div>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
