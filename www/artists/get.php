<?
use function Safe\session_unset;

$isReviewerView = Session::$User?->Benefits->CanReviewArtwork ?? false;
$isAdminView = Session::$User?->Benefits->CanReviewOwnArtwork ?? false;
$submitterUserId = Session::$User?->Benefits->CanUploadArtwork ? Session::$User->UserId : null;
$isSubmitterView = !$isReviewerView && $submitterUserId !== null;

$artworkFilterType = Enums\ArtworkFilterType::Approved;

if($isReviewerView){
	$artworkFilterType = Enums\ArtworkFilterType::Admin;
}

if($isSubmitterView){
	$artworkFilterType = Enums\ArtworkFilterType::ApprovedSubmitter;
}

session_start();

$isArtistDeleted = HttpInput::Bool(SESSION, 'is-artist-deleted') ?? false;
$deletedArtist = HttpInput::SessionObject('deleted-artist', Artist::class);
$isAlternateNameAdded = HttpInput::Bool(SESSION, 'is-alternate-name-added') ?? false;

try{
	$artworks = Artwork::GetAllByArtist(HttpInput::Str(GET, 'artist-url-name'), $artworkFilterType, $submitterUserId);

	if(sizeof($artworks) == 0){
		throw new Exceptions\ArtistNotFoundException();
	}

	$artist = $artworks[0]->Artist;

	if($isArtistDeleted){
		session_unset();
	}
}
catch(Exceptions\ArtistNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
?><?= Template::Header(title: 'Artwork by ' . $artist->Name, css: ['/css/artwork.css']) ?>
<main class="artworks">
	<section class="narrow">
		<h1>Artwork by <?= Formatter::EscapeHtml($artist->Name) ?></h1>

		<? if($isArtistDeleted && $deletedArtist !== null){ ?>
			<p class="message success">Artist deleted: <?= $deletedArtist->Name ?><? if($isAlternateNameAdded){ ?>. An alternate name (A.K.A.) was added.<? } ?></p>
		<? } ?>

		<?= Template::ImageCopyrightNotice() ?>

		<?= Template::ArtworkList(artworks: $artworks) ?>

		<? if($isAdminView){ ?>
			<h2>Admin</h2>
			<p><a href="<?= $artist->DeleteUrl ?>">Delete artist and reassign artwork</a></p>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
