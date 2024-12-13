<?
use function Safe\session_unset;

session_start();

$isCreated = HttpInput::Bool(SESSION, 'is-ebook-placeholder-created') ?? false;
$exception = HttpInput::SessionObject('exception', Exceptions\AppException::class);
$ebook = HttpInput::SessionObject('ebook', Ebook::class);

try{
	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanCreateEbookPlaceholders){
		throw new Exceptions\InvalidPermissionsException();
	}

	if($isCreated){
		// We got here because an `Ebook` was successfully created.
		http_response_code(Enums\HttpCode::Created->value);
		$createdEbook = $ebook;
		$ebook = null;
		session_unset();
	}
	elseif($exception){
		// We got here because an `Ebook` submission had errors and the user has to try again.
		http_response_code(Enums\HttpCode::UnprocessableContent->value);
		session_unset();
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

		<? if($isCreated && isset($createdEbook)){ ?>
			<p class="message success">Ebook Placeholder created: <a href="<?= $createdEbook->Url ?>"><?= Formatter::EscapeHtml($createdEbook->Title) ?></a>!</p>
		<? } ?>

		<form class="create-update-ebook-placeholder" method="<?= Enums\HttpMethod::Post->value ?>" action="/ebook-placeholders" autocomplete="off">
			<?= Template::EbookPlaceholderForm(['ebook' => $ebook]) ?>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
