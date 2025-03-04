<?
$isReviewerView = Session::$User?->Benefits->CanReviewArtwork ?? false;
$submitterUserId = Session::$User?->Benefits->CanUploadArtwork ? Session::$User->UserId : null;
$isSubmitterView = !$isReviewerView && $submitterUserId !== null;

$artworkFilterType = Enums\ArtworkFilterType::Approved;

if($isReviewerView){
	$artworkFilterType = Enums\ArtworkFilterType::Admin;
}

if($isSubmitterView){
	$artworkFilterType = Enums\ArtworkFilterType::ApprovedSubmitter;
}

try{
	$artworks = Artwork::GetAllByArtist(HttpInput::Str(GET, 'artist-url-name'), $artworkFilterType, $submitterUserId);

	if(sizeof($artworks) == 0){
		throw new Exceptions\ArtistNotFoundException();
	}
}
catch(Exceptions\ArtistNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
?><?= Template::Header(title: 'Artwork by ' . $artworks[0]->Artist->Name, css: ['/css/artwork.css']) ?>
<main class="artworks">
	<section class="narrow">
		<h1>Artwork by <?= Formatter::EscapeHtml($artworks[0]->Artist->Name) ?></h1>

		<?= Template::ImageCopyrightNotice() ?>

		<?= Template::ArtworkList(artworks: $artworks) ?>
	</section>
</main>
<?= Template::Footer() ?>
