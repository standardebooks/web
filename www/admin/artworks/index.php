<?
require_once('Core.php');

use function Safe\session_unset;

session_start();

$approvedArtworkId = $_SESSION['approved-artwork-id'] ?? null;
$declinedArtworkId = $_SESSION['declined-artwork-id'] ?? null;

if($approvedArtworkId || $declinedArtworkId){
	http_response_code(201);
	session_unset();
}

$approvedArtwork = null;
if($approvedArtworkId){
	$approvedArtwork = Artwork::Get($approvedArtworkId);
}

$declinedArtwork = null;
if($declinedArtworkId){
	$declinedArtwork = Artwork::Get($declinedArtworkId);
}

$page = HttpInput::Int(GET, 'page') ?? 1;
if($page <= 0){
	$page = 1;
}
$perPage = 10;

$unverifiedArtworks = Db::Query('
				SELECT *
				from Artworks
				where status = "unverified"
				order by Created asc
			', [], 'Artwork');
$count = sizeof($unverifiedArtworks);
$pages = ceil($count / $perPage);

$unverifiedArtworks = array_slice($unverifiedArtworks, ($page - 1) * $perPage, $perPage);

?><?= Template::Header(['title' => 'Review Artwork Queue', 'artwork' => true, 'highlight' => '', 'description' => 'The queue of unverified artwork.']) ?>
<main class="artworks">
	<section class="narrow">
		<hgroup>
			<h1>Review Artwork Queue</h1>
		</hgroup>

		<section>
			<? if($approvedArtwork){ ?>
			<p class="message success">
				<a href="<?= $approvedArtwork->Url ?>" property="schema:name"><?= Formatter::ToPlainText($approvedArtwork->Name) ?></a> approved.
			</p>
			<? } ?>
			<? if($declinedArtwork){ ?>
			<p class="message">
				“<?= Formatter::ToPlainText($declinedArtwork->Name) ?>” declined.
			</p>
			<? } ?>
			<? if($count == 0){ ?>
				<p>No artwork to review.</p>
			<? }else{ ?>
				<?= Template::ArtworkList(['artworks' => $unverifiedArtworks, 'useAdminUrl' => true]) ?>
			<? } ?>
		</section>
	</section>

	<? if($count > 0){ ?>
	<nav>
		<a<? if($page > 1){ ?> href="/admin/artworks/?page=<?= $page - 1 ?>" rel="prev"<? }else{ ?> aria-disabled="true"<? } ?>>Back</a>
		<ol>
		<? for($i = 1; $i < $pages + 1; $i++){ ?>
			<li<? if($page == $i){ ?> class="highlighted"<? } ?>><a href="/admin/artworks/?page=<?= $i ?>"><?= $i ?></a></li>
		<? } ?>
		</ol>
		<a<? if($page < ceil($count / $perPage)){ ?> href="/admin/artworks/?page=<?= $page + 1 ?>" rel="next"<? }else{ ?> aria-disabled="true"<? } ?>>Next</a>
	</nav>
	<? } ?>

</main>
<?= Template::Footer() ?>
