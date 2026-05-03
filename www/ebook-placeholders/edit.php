<?
/**
 * GET		/ebooks/:url-path/edit
 */

use function Safe\session_start;
use function Safe\session_unset;

try{
	session_start();

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

	$exception = HttpInput::SessionObject('exception', Exceptions\AppException::class);
	$editedEbook = HttpInput::SessionObject('ebook', Ebook::class);

	if($editedEbook === null){
		$editedEbook = $ebook;
	}

	// We got here because an operation had errors and the user has to try again.
	if($exception){
		http_response_code(Enums\HttpCode::UnprocessableContent->value);
		session_unset();
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
	title: 'Edit ' . $ebook->Title,
	css: ['/css/ebook-placeholder.css'],
	description: 'Edit ' . $ebook->Title
) ?>
<main>
	<section class="narrow">
		<nav class="breadcrumbs" aria-label="Breadcrumbs">
			<a href="<?= $ebook->AuthorsUrl ?>"><?= $ebook->AuthorsString ?></a> →
			<a href="<?= $ebook->Url ?>"><?= Formatter::EscapeHtml($ebook->Title) ?></a> →
		</nav>
		<h1>Edit</h1>

		<?= Template::Error(exception: $exception) ?>

		<form class="create-update-ebook-placeholder" method="<?= Enums\HttpMethod::Post->value ?>" action="<?= $ebook->Url ?>" autocomplete="off">
			<input type="hidden" name="_method" value="<?= Enums\HttpMethod::Patch->value ?>" />
			<?= Template::EbookPlaceholderForm(ebook: $editedEbook, isEditForm: true) ?>
			<div class="footer">
				<button>Save</button>
			</div>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
