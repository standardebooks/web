<?
/**
 * GET		/artworks/:artist-url-name
 */

use function Safe\session_start;
use function Safe\session_unset;

try{
	session_start();

	/** @var Artist $artist The `Artist` for this request, passed in from the router. */
	$artist = $resource ?? throw new Exceptions\ArtistNotFoundException();

	$isReviewerView = Session::$User?->Benefits->CanReviewArtwork ?? false;
	$isAdminView = Session::$User?->Benefits->IsArtworkAdmin ?? false;
	$submitterUserId = Session::$User?->Benefits->CanUploadArtwork ? Session::$User->UserId : null;
	$isSubmitterView = !$isReviewerView && $submitterUserId !== null;

	$artworkFilterType = Enums\ArtworkFilterType::Approved;

	if($isReviewerView){
		$artworkFilterType = Enums\ArtworkFilterType::Admin;
	}

	if($isSubmitterView){
		$artworkFilterType = Enums\ArtworkFilterType::ApprovedSubmitter;
	}

	$isArtistDeleted = Http::$Request->Session->Get('artist/delete/is-deleted', 'bool') ?? false;
	$deletedArtist = Http::$Request->Session->Get('deleted-artist', Artist::class);
	$isAlternateNameAdded = Http::$Request->Session->Get('artist/delete/is-alternate-name-added', 'bool') ?? false;

	$artworks = Artwork::GetAllByArtist(Http::$Request->QueryString->Get('artist-url-name'), $artworkFilterType, $submitterUserId);

	if(sizeof($artworks) == 0){
		throw new Exceptions\ArtistNotFoundException();
	}

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
				<ul role="menu">
					<li><a href="<?= $artist->DeleteUrl ?>">Delete artist</a></li>
				</ul>
				<dl>
					<dt>Artist ID:</dt>
					<dd><?= $artist->ArtistId ?></dd>
				</dl>
			</section>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
