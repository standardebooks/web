<?
use function Safe\session_unset;

session_start();

$artwork = null;
$status = HttpInput::Str(SESSION, 'status', false);
$page = HttpInput::Int(GET, 'page') ?? 1;
$perPage = 10;
$hasNextPage = false;
$unverifiedArtworks = [];

try{
	if($GLOBALS['User'] === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!$GLOBALS['User']->Benefits->CanReviewArtwork){
		throw new Exceptions\InvalidPermissionsException();
	}

	if($status !== null){
		$artwork = Artwork::Get(HttpInput::Int(SESSION, 'artwork-id'));

		http_response_code(201);
		session_unset();
	}

	if($page <= 0){
		$page = 1;
	}

	$unverifiedArtworks = Db::Query('
					SELECT *
					from Artworks
					where Status = "unverified"
					order by Created asc
					limit ?, ?
				', [($page - 1) * $perPage, $perPage + 1], 'Artwork');

	if(sizeof($unverifiedArtworks) > $perPage){
		array_pop($unverifiedArtworks);
		$hasNextPage = true;
	}
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::Emit403(); // No permissions to submit artwork
}

?><?= Template::Header(['title' => 'Artwork Review Queue', 'artwork' => true, 'highlight' => '', 'description' => 'The queue of unverified artwork.']) ?>
<main class="artworks">
	<section class="narrow">
		<h1>Artwork Review Queue</h1>

		<? if($status == COVER_ARTWORK_STATUS_APPROVED){ ?>
		<p class="message success">
			<? if($artwork !== null){ ?>
				<i><a href="<?= $artwork->Url ?>" property="schema:name"><?= Formatter::ToPlainText($artwork->Name) ?></a></i> approved.
			<? }else{ ?>
				Artwork approved.
			<? } ?>
		</p>
		<? } ?>
		<? if($status == COVER_ARTWORK_STATUS_DECLINED){ ?>
		<p class="message">
			<? if($artwork !== null){ ?>
				<i><?= Formatter::ToPlainText($artwork->Name) ?></i> declined.
			<? }else{ ?>
				Artwork declined.
			<? } ?>
		</p>
		<? } ?>
		<? if(sizeof($unverifiedArtworks) == 0){ ?>
			<p>No artwork to review.</p>
		<? }else{ ?>
			<?= Template::ArtworkList(['artworks' => $unverifiedArtworks, 'useAdminUrl' => true]) ?>
		<? } ?>
	</section>

	<nav>
		<a<? if($page > 1){ ?> href="/admin/artworks/?page=<?= $page - 1 ?>" rel="prev"<? }else{ ?> aria-disabled="true"<? } ?>>Back</a>
		<a<? if($hasNextPage){ ?> href="/admin/artworks/?page=<?= $page + 1 ?>" rel="next"<? }else{ ?> aria-disabled="true"<? } ?>>Next</a>
	</nav>
</main>
<?= Template::Footer() ?>
