<?
use function Safe\session_unset;

session_start();

$exception = $_SESSION['exception'] ?? null;
/** @var Artwork $artwork */
$artwork = $_SESSION['artwork'] ?? null;

try{
	if($GLOBALS['User'] === null){
		throw new Exceptions\LoginRequiredException();
	}

	if($artwork === null){
		$artwork = Artwork::GetByUrl(HttpInput::Str(GET, 'artist-url-name') ?? '', HttpInput::Str(GET, 'artwork-url-name') ?? '');
	}

	$isEditingAllowed = ($artwork->Status == ArtworkStatus::Unverified) && ($GLOBALS['User']->Benefits->CanReviewArtwork || ($artwork->SubmitterUserId == $GLOBALS['User']->UserId));
	if(!$isEditingAllowed){
		throw new Exceptions\InvalidPermissionsException();
	}

	$isAdminView = $GLOBALS['User']->Benefits->CanReviewArtwork ?? false;

	// We got here because an artwork update had errors and the user has to try again
	if($exception){
		http_response_code(422);
		session_unset();
	}
}
catch(Exceptions\ArtworkNotFoundException){
	Template::Emit404();
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::Emit403(); // No permissions to submit artwork
}

?>
<?= Template::Header(
	[
		'title' => 'Edit Artwork',
		'artwork' => true,
		'highlight' => '',
		'description' => 'Edit public domain artwork to the database for use as cover art.'
	]
) ?>
<main>
	<section class="narrow">
		<h1>Edit Artwork</h1>

		<?= Template::Error(['exception' => $exception]) ?>

		<a href="<?= $artwork->ImageUrl ?>">
			<picture>
				<source srcset="<?= $artwork->Thumb2xUrl ?> 2x, <?= $artwork->ThumbUrl ?> 1x" type="image/jpg"/>
				<img src="<?= $artwork->ThumbUrl ?>" alt="" property="schema:image"/>
			</picture>
		</a>

		<form class="create-update-artwork" method="post" action="<?= $artwork->Url ?>" enctype="multipart/form-data">
			<input type="hidden" name="_method" value="PUT" />

			<?= Template::ArtworkCreateEditFields(
				[
					'artwork' => $artwork,
					'imageRequired' => false,
					'isAdminView' => $isAdminView
				]
			) ?>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
