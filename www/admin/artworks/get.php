<?
require_once('Core.php');

$artworkId = HttpInput::Int(GET, 'artworkid');

try{
	$artwork = Artwork::Get($artworkId);
	$existingArtwork = Artwork::GetByUrlPath($artwork->Artist->UrlName, $artwork->UrlName);
}
catch(Exceptions\SeException){
	Template::Emit404();
}

?><?= Template::Header(['title' => 'Review Artwork', 'artwork' => true, 'highlight' => '', 'description' => 'Unapproved artwork.']) ?>
<main class="artworks">
	<section class="narrow">
		<?= Template::ArtworkDetail(['artwork' => $artwork]) ?>
	</section>
	<h2>Review</h2>
	<? if($existingArtwork === null){ ?>
	<p>Review the metadata and PD proof for this artwork submission. Approve to make it available for future producers.</p>
	<? }else{ ?>
	<p>Artwork cannot be approved because <a href="<?= $existingArtwork->Url ?>"><?= Formatter::ToPlainText($existingArtwork->Name) ?> by <?= Formatter::ToPlainText($existingArtwork->Artist->Name) ?></a> exists with status: <?= Template::ArtworkStatus(['artwork' => $existingArtwork]) ?>. Contact the site admin if it should be approved with updated metadata, e.g., an altered title.</p>
	<? } ?>
	<form method="post" action="/admin/artworks/<?= $artwork->ArtworkId ?>">
		<input type="hidden" name="_method" value="PATCH" />
		<button name="status" value="approved" <? if($existingArtwork !== null){ ?>disabled="disabled"<? } ?>>Approve</button>
		<button name="status" value="declined" class="decline-button">Decline</button>
	</form>
</main>
<?= Template::Footer() ?>
