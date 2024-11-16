<?
use function Safe\session_unset;

session_start();

$isCreated = HttpInput::Bool(SESSION, 'is-created') ?? false;
/** @var ?\Exception $exception */
$exception = $_SESSION['exception'] ?? null;
/** @var ?Artwork $artwork */
$artwork = $_SESSION['artwork'] ?? null;

try{
	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanUploadArtwork){
		throw new Exceptions\InvalidPermissionsException();
	}

	// We got here because an artwork was successfully submitted.
	if($isCreated){
		http_response_code(201);
		$artwork = null;
		session_unset();
	}

	// We got here because an artwork submission had errors and the user has to try again.
	if($exception){
		http_response_code(422);
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
	Template::Emit403(); // No permissions to submit artwork.
}

?>
<?= Template::Header(
	[
		'title' => 'Submit an Artwork',
		'artwork' => true,
		'highlight' => '',
		'description' => 'Submit public domain artwork to the database for use as cover art.'
	]
) ?>
<main>
	<section class="narrow">
		<h1>Submit an Artwork</h1>

		<?= Template::Error(['exception' => $exception]) ?>

		<? if($isCreated){ ?>
			<p class="message success">Artwork submitted!</p>
		<? } ?>

		<form class="create-update-artwork" method="post" action="/artworks" enctype="multipart/form-data" autocomplete="off">
			<?= Template::ArtworkForm(['artwork' => $artwork]) ?>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
