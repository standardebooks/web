<?
use function Safe\session_start;
use function Safe\session_unset;

try{
	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanUploadArtwork){
		throw new Exceptions\InvalidPermissionsException();
	}

	session_start();

	$isCreated = HttpInput::Bool(SESSION, 'is-artwork-created') ?? false;
	$exception = HttpInput::SessionObject('exception', Exceptions\AppException::class);
	$artwork = HttpInput::SessionObject('artwork', Artwork::class);

	if($isCreated){
		// We got here because an `Artwork` was successfully submitted.
		http_response_code(Enums\HttpCode::Created->value);
		$artwork = null;
		session_unset();
	}
	elseif($exception){
		// We got here because an `Artwork` submission had errors and the user has to try again.
		http_response_code(Enums\HttpCode::UnprocessableContent->value);
		session_unset();
	}

	if($artwork === null){
		$artwork = new Artwork();
		$artwork->Artist = new Artist();

		if(Session::$User->Benefits->CanReviewOwnArtwork){
			$artwork->Status = Enums\ArtworkStatusType::Approved;
		}
	}
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden); // No permissions to submit artwork.
}

?>
<?= Template::Header(
		title: 'Submit an Artwork',
		css: ['/css/artwork.css'],
		description: 'Submit public domain artwork to the database for use as cover art.'
) ?>
<main>
	<section class="narrow">
		<nav class="breadcrumbs" aria-label="Breadcrumbs">
			<a href="/artworks">Artworks</a> →
		</nav>
		<h1>Submit an Artwork</h1>

		<?= Template::Error(exception: $exception) ?>

		<? if($isCreated){ ?>
			<p class="message success">Artwork submitted!</p>
		<? } ?>

		<form class="create-update-artwork" method="<?= Enums\HttpMethod::Post->value ?>" action="/artworks" enctype="multipart/form-data" autocomplete="off">
			<?= Template::ArtworkForm(artwork: $artwork) ?>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
