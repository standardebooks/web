<?
/**
 * GET		/artworks/:artist-url-name/:artwork-url-name/edit
 */

use function Safe\session_start;
use function Safe\session_unset;

try{
	session_start();

	$originalArtwork = Artwork::GetByUrl(Http::$Request->QueryString->Get('artist-url-name'), Http::$Request->QueryString->Get('artwork-url-name'));

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!$originalArtwork->CanBeEditedBy(Session::$User)){
		throw new Exceptions\PermissionsInvalidException();
	}

	$exception = Http::$Request->Session->Get('artwork/edit/exception', Exceptions\AppException::class);
	$artwork = Http::$Request->Session->Get('artwork', Artwork::class) ?? $originalArtwork;

	if($exception){
		// We got here because an operation had errors and the user has to try again.
		if($exception instanceof Exceptions\ValidationException && $exception->Has(Exceptions\RequestInvalidException::class)){
			http_response_code(Enums\HttpCode::ContentTooLarge->value);
		}
		else{
			http_response_code(Enums\HttpCode::UnprocessableContent->value);
		}

		session_unset();
	}
}
catch(Exceptions\ArtworkNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden); // No permissions to edit artwork.
}
?>
<?= Template::Header(
		title: 'Edit ' . $originalArtwork->Name . ', by ' . $originalArtwork->Artist->Name,
		css: ['/css/artwork.css'],
		description: 'Edit ' . $originalArtwork->Name . ', by ' . $originalArtwork->Artist->Name . ' in the Standard Ebooks cover art database.'
) ?>
<main>
	<section class="narrow">
		<nav class="breadcrumbs" aria-label="Breadcrumbs">
				<a href="/artworks">Artworks</a> →
				<a href="<?= $originalArtwork->Artist->Url ?>"><?= Formatter::EscapeHtml($originalArtwork->Artist->Name) ?></a> →
				<a href="<?= $originalArtwork->Url ?>"><?= Formatter::EscapeHtml($originalArtwork->Name) ?></a> →
			</nav>
		<h1>Edit</h1>

		<?= Template::Error(exception: $exception) ?>

		<picture>
			<source srcset="<?= $originalArtwork->Thumb2xUrl ?> 2x, <?= $originalArtwork->ThumbUrl ?> 1x" type="image/jpg"/>
			<img src="<?= $originalArtwork->ThumbUrl ?>" alt="" property="schema:image"/>
		</picture>

		<form class="create-update-artwork" method="<?= Enums\HttpMethod::Post->value ?>" action="<?= $originalArtwork->Url ?>" enctype="multipart/form-data" autocomplete="off">
			<input type="hidden" name="_method" value="<?= Enums\HttpMethod::Patch->value ?>" />
			<?= Template::ArtworkForm(artwork: $artwork, isEditForm: true) ?>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
