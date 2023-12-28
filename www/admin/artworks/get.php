<?
use function Safe\session_unset;

session_start();

$exception = $_SESSION['exception'] ?? null;

try{
	$artwork = Artwork::Get(HttpInput::Int(GET, 'artworkid'));

	// The artwork is already approved or in use, so redirect to its public page
	if($artwork->Status == COVER_ARTWORK_STATUS_APPROVED || $artwork->Status == COVER_ARTWORK_STATUS_IN_USE){
		http_response_code(302);
		header('Location: ' . $artwork->Url);
		exit();
	}

	// We got here because an artwork submission had errors and the user has to try again
	if($exception){
		http_response_code(422);
		session_unset();
	}
}
catch(Exceptions\AppException){
	Template::Emit404();
}

?><?= Template::Header(['title' => 'Review Artwork', 'artwork' => true, 'highlight' => '', 'description' => 'Unverified artwork.']) ?>
<main class="artworks">
	<?= Template::Error(['exception' => $exception]) ?>
	<section class="narrow">
		<?= Template::ArtworkDetail(['artwork' => $artwork]) ?>
		<? if($artwork->Status == COVER_ARTWORK_STATUS_DECLINED){ ?>
		<h2>Status</h2>
		<p>This artwork has been declined by a reviewer.</p>
		<? } ?>
		<? if($artwork->Status == COVER_ARTWORK_STATUS_UNVERIFIED){ ?>
		<h2>Review</h2>
		<p>Review the metadata and PD proof for this artwork submission. Approve to make it available for future producers.</p>
		<form method="post" action="/admin/artworks/<?= $artwork->ArtworkId ?>">
			<input type="hidden" name="_method" value="PATCH" />
			<button name="status" value="<?= COVER_ARTWORK_STATUS_APPROVED ?>">Approve</button>
			<button name="status" value="<?= COVER_ARTWORK_STATUS_DECLINED ?>" class="decline-button">Decline</button>
		</form>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
