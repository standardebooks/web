<?
$isReviewerView = Session::$User?->Benefits?->CanReviewArtwork ?? false;
$submitterUserId = Session::$User?->Benefits?->CanUploadArtwork ? Session::$User->UserId : null;
$isSubmitterView = !$isReviewerView && $submitterUserId !== null;

$filterArtworkStatus = 'all';

if($isReviewerView){
	$filterArtworkStatus = 'all-admin';
}

if($isSubmitterView){
	$filterArtworkStatus = 'all-submitter';
}

try{
	$artworks = Library::GetArtworksByArtist(HttpInput::Str(GET, 'artist-url-name'), $filterArtworkStatus, $submitterUserId);

	if(sizeof($artworks) == 0){
		throw new Exceptions\ArtistNotFoundException();
	}
}
catch(Exceptions\ArtistNotFoundException){
	Template::Emit404();
}
?><?= Template::Header(['title' => 'Artwork by ' . $artworks[0]->Artist->Name, 'artwork' => true]) ?>
<main class="artworks">
	<section class="narrow">
		<h1>Artwork by <?= Formatter::EscapeHtml($artworks[0]->Artist->Name) ?></h1>

		<?= Template::ImageCopyrightNotice() ?>

		<?= Template::ArtworkList(['artworks' => $artworks]) ?>
	</section>
</main>
<?= Template::Footer() ?>
