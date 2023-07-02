<?
require_once('Core.php');

$artistUrlName = HttpInput::Str(GET, 'artist');
$artworkUrlName = HttpInput::Str(GET, 'artwork');
$slug = $artistUrlName . '/' . $artworkUrlName;


$artwork = Library::GetArtworkBySlug($slug);

if($artwork === null){
	Template::Emit404();
}

?><?= Template::Header(['title' => $artwork->Name]) ?>
<main class="artworks">
	<section class="narrow">
		<?= Template::ArtworkDetail(['artwork' => $artwork]) ?>
	</section>
</main>
<?= Template::Footer() ?>
