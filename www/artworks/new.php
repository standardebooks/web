<?
/**
 * GET		/artworks/new
 */

use function Safe\session_start;
use function Safe\session_unset;

try{
	session_start();

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanUploadArtwork){
		throw new Exceptions\PermissionsInvalidException();
	}

	unset($_SESSION['artwork/create/image-token-secret']);

	$isCreated = Http::$Request->Session->Get('artwork/create/is-created', 'bool') ?? false;
	$exception = Http::$Request->Session->Get('artwork/create/exception', Exceptions\AppException::class);
	$artwork = Http::$Request->Session->Get('artwork/create/artwork', Artwork::class);
	$stagedImagePath = Http::$Request->Session->Get('artwork/create/image-path');
	$stagedImageToken = null;

	if($isCreated){
		// We got here because an `Artwork` was successfully submitted.
		http_response_code(Enums\HttpCode::Created->value);
		$artwork = null;
		session_unset();
	}
	elseif($exception){
		// We got here because an operation had errors and the user has to try again.
		if($exception instanceof Exceptions\ValidationException && $exception->Has(Exceptions\RequestInvalidException::class)){
			http_response_code(Enums\HttpCode::ContentTooLarge->value);
		}
		else{
			http_response_code(Enums\HttpCode::UnprocessableContent->value);
		}

		session_unset();
		if($stagedImagePath !== null && is_file($stagedImagePath)){
			$imageTokenSecret = bin2hex(random_bytes(32));
			try{
				$stagedImageToken = UploadTempDirectory::CreateToken($stagedImagePath, $imageTokenSecret);
				$_SESSION['artwork/create/image-token-secret'] = $imageTokenSecret;
			}
			catch(Exceptions\TempDirectoryException){
				// The staged image is not available for reuse.
			}
		}
	}

	if($artwork === null){
		$artwork = new Artwork();
		$artwork->Artist = new Artist();

		if(Session::$User->Benefits->IsArtworkAdmin){
			$artwork->Status = Enums\ArtworkStatusType::Approved;
		}
	}
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
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
			<?= Template::ArtworkForm(artwork: $artwork, stagedImageToken: $stagedImageToken) ?>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
