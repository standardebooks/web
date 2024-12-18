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
	[
		'title' => 'Edit Ebook Placeholder for ' . $ebook->Title,
		'css' => ['/css/ebook-placeholder.css'],
		'highlight' => '',
		'description' => 'Edit the ebook placeholder for ' . $ebook->Title
	]
) ?>
<main>
	<section class="narrow">
		<h1>Edit Ebook Placeholder</h1>

		<?= Template::Error(['exception' => $exception]) ?>

		<form class="create-update-ebook-placeholder" method="<?= Enums\HttpMethod::Post->value ?>" action="<?= $ebook->Url ?>" autocomplete="off">
			<input type="hidden" name="_method" value="<?= Enums\HttpMethod::Put->value ?>" />
			<?= Template::EbookPlaceholderForm(['ebook' => $ebook, 'isEditForm' => true]) ?>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
