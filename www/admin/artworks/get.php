<?
require_once('Core.php');

$artworkId = HttpInput::Int(GET, 'artworkid');

try{
	$artwork = Artwork::Get($artworkId);
}
catch(Exceptions\SeException $ex){
	Template::Emit404();
}

?><?= Template::Header(['title' => 'Review Artwork', 'highlight' => '', 'description' => 'Unapproved artwork.']) ?>
<main class="artworks">
	<section>
		<?= Template::ArtworkDetail(['artwork' => $artwork]) ?>
	</section>
	<h2>Review</h2>
	<p>Review the metadata and PD proof for this artwork submission. Approve to make it available for future producers.<p>
	<form method="post" action="/admin/artworks/<?= $artwork->ArtworkId ?>">
		<input type="hidden" name="_method" value="PATCH">
		<button name="status" value="approved">Approve</button>
		<button name="status" value="declined" class="decline-button">Decline</button>
	</form>
</main>
<?= Template::Footer() ?>
