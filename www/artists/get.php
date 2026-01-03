<?
use function Safe\session_start;
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
		<nav class="breadcrumbs" aria-label="Breadcrumbs">
			<a href="/artworks">Artworks</a> →
		</nav>
		<h1>Artwork by <?= Formatter::EscapeHtml($artist->Name) ?></h1>
		<? if(sizeof($artist->AlternateNames) > 0){ ?>
			<p>
				<i>Also known as <?= Formatter::EscapeHtml($artist->AlternateNamesString) ?>.</i>
			</p>
		<? } ?>
		<? if($isArtistDeleted && $deletedArtist !== null){ ?>
			<p class="message success"><?= $deletedArtist->Name ?> has been deleted<? if($isAlternateNameAdded){ ?> and their name has been added as an alternate name of <?= Formatter::EscapeHtml($artist->Name) ?><? } ?>.</p>
		<? } ?>

		<?= Template::ImageCopyrightNotice() ?>

		<?= Template::ArtworkList(artworks: $artworks) ?>

		<? if($isAdminView){ ?>
			<section class="admin">
				<h2>Metadata</h2>
				<p>
					<a href="<?= $artist->DeleteUrl ?>">Delete artist</a>
				</p>
				<table class="admin-table">
					<tbody>
						<tr>
							<td>Artist ID:</td>
							<td><?= $artist->ArtistId ?></td>
						</tr>
					</tbody>
				</table>
			</section>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
