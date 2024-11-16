<?
use function Safe\session_unset;

session_start();

/** @var ?\Exception $exception */
$exception = $_SESSION['exception'] ?? null;
/** @var ?Artwork $artwork */
$artwork = $_SESSION['artwork'] ?? null;

try{
	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if($artwork === null){
		$artwork = Artwork::GetByUrl(HttpInput::Str(GET, 'artist-url-name'), HttpInput::Str(GET, 'artwork-url-name'));
	}

	if(!$artwork->CanBeEditedBy(Session::$User)){
		throw new Exceptions\InvalidPermissionsException();
	}

	// We got here because an artwork update had errors and the user has to try again.
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
	Template::Emit403(); // No permissions to edit artwork.
}

?>
<?= Template::Header(
	[
		'title' => 'Edit ' . $artwork->Name . ', by ' . $artwork->Artist->Name,
		'artwork' => true,
		'highlight' => '',
		'description' => 'Edit ' . $artwork->Name . ', by ' . $artwork->Artist->Name . ' in the Standard Ebooks cover art database.'
	]
) ?>
<main>
	<section class="narrow">
		<h1>Edit Artwork</h1>

		<?= Template::Error(['exception' => $exception]) ?>

		<picture>
			<source srcset="<?= $artwork->Thumb2xUrl ?> 2x, <?= $artwork->ThumbUrl ?> 1x" type="image/jpg"/>
			<img src="<?= $artwork->ThumbUrl ?>" alt="" property="schema:image"/>
		</picture>

		<form class="create-update-artwork" method="post" action="<?= $artwork->Url ?>" enctype="multipart/form-data" autocomplete="off">
			<input type="hidden" name="_method" value="PUT" />
			<?= Template::ArtworkForm(['artwork' => $artwork, 'isEditForm' => true]) ?>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
