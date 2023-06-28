<?
require_once('Core.php');

session_start();

$approvedMessage = $_SESSION['approved-message'] ?? null;
$declinedMessage = $_SESSION['declined-message'] ?? null;

if ($approvedMessage || $declinedMessage){
	http_response_code(201);
	session_unset();
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

?><?= Template::Header(['title' => 'Review Artwork Queue', 'highlight' => '', 'description' => 'The queue of unapproved artwork.']) ?>
<main class="artworks">
	<section class="narrow">
		<hgroup>
			<h1>Review Artwork Queue</h1>
		</hgroup>

		<section id="unapproved-artwork">
			<? if ($approvedMessage){ ?>
			<p class="message success">
				<?= $approvedMessage ?>
			</p>
			<? } ?>
			<? if ($declinedMessage){ ?>
			<p class="message">
				<?= $declinedMessage ?>
			</p>
			<? } ?>
			<? if($count > 0){ ?>
			<?= Template::AdminArtworkList(['artworks' => $unverifiedArtworks]) ?>
			<? }else{ ?>
			<p>No artwork to review.</p>
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
