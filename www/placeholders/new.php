<?
use function Safe\session_unset;

session_start();

$isCreated = HttpInput::Bool(SESSION, 'is-ebook-created') ?? false;
$exception = HttpInput::SessionObject('exception', Exceptions\AppException::class);
$ebook = HttpInput::SessionObject('ebook', Ebook::class);

try{
	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanCreateEbookPlaceholder){
		throw new Exceptions\InvalidPermissionsException();
	}

	// We got here because an ebook was successfully created.
	if($isCreated){
		http_response_code(Enums\HttpCode::Created->value);
		$ebook = null;
		session_unset();
	}

	// We got here because an ebook creation had errors and the user has to try again.
	if($exception){
		http_response_code(Enums\HttpCode::UnprocessableContent->value);
		session_unset();
	}

	if($ebook === null){
		$ebook = new Ebook();
	}
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::Emit403(); // No permissions to create an ebook placeholder.
}

?>
<?= Template::Header(
	[
		'title' => 'Create an Ebook Placeholder',
		'css' => ['/css/ebook-placeholder.css'],
		'highlight' => '',
		'description' => 'Create a placeholder for an ebook not yet in the collection.'
	]
) ?>
<main>
	<section class="narrow">
		<h1>Create an Ebook Placeholder</h1>

		<?= Template::Error(['exception' => $exception]) ?>

		<? if($isCreated){ ?>
			<p class="message success">Ebook Placeholder created!</p>
		<? } ?>

		<form class="create-update-ebook-placeholder" method="<?= Enums\HttpMethod::Post->value ?>" action="/placeholders" enctype="multipart/form-data" autocomplete="off">
			<?= Template::EbookPlaceholderForm(['ebook' => $ebook]) ?>
		</form>
	</section>
</main>
<?= Template::Footer() ?>