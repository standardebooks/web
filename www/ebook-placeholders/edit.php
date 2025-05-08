<?
use function Safe\session_start;
use function Safe\session_unset;

session_start();

/** @var string $identifier Passed from script this is included from. */
$ebook = HttpInput::SessionObject('ebook', Ebook::class);
$exception = HttpInput::SessionObject('exception', Exceptions\AppException::class);

try{
	if($ebook === null){
		$ebook = Ebook::GetByIdentifier($identifier);
	}

	if(!$ebook->IsPlaceholder() || $ebook->EbookPlaceholder === null){
		throw new Exceptions\EbookNotFoundException();
	}

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditEbookPlaceholders){
		throw new Exceptions\InvalidPermissionsException();
	}

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
		<nav class="breadcrumbs">
			<a href="<?= $ebook->AuthorsUrl ?>"><?= $ebook->AuthorsString ?></a> →
			<a href="<?= $ebook->Url ?>"><?= Formatter::EscapeHtml($ebook->Title) ?></a> →
		</nav>
		<h1>Edit</h1>

		<?= Template::Error(exception: $exception) ?>

		<form class="create-update-ebook-placeholder" method="<?= Enums\HttpMethod::Post->value ?>" action="<?= $ebook->Url ?>" autocomplete="off">
			<input type="hidden" name="_method" value="<?= Enums\HttpMethod::Put->value ?>" />
			<?= Template::EbookPlaceholderForm(ebook: $ebook, isEditForm: true) ?>
			<div class="footer">
				<button>Save</button>
			</div>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
