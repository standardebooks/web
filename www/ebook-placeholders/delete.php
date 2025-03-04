<?
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
	title: 'Delete ' . $ebook->Title,
	css: ['/css/ebook-placeholder.css'],
	description: 'Delete ' . $ebook->Title
) ?>
<main>
	<section class="narrow">
		<nav class="breadcrumbs">
			<a href="<?= $ebook->AuthorsUrl ?>"><?= $ebook->AuthorsString ?></a> →
			<a href="<?= $ebook->Url ?>"><?= Formatter::EscapeHtml($ebook->Title) ?></a> →
		</nav>
		<h1>Delete</h1>

		<?= Template::Error(exception: $exception) ?>

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
