<?
require_once('Core.php');

$artistUrlName = HttpInput::Str(GET, 'artist');
$artworkUrlName = HttpInput::Str(GET, 'artwork');

$artwork = Artwork::GetByUrlPath($artistUrlName, $artworkUrlName);

if($artwork === null){
	Template::Emit404();
}

?><?= Template::Header(['title' => $artwork->Name, 'artwork' => true]) ?>
<main class="artworks">
	<section class="narrow">
		<?= Template::ArtworkDetail(['artwork' => $artwork]) ?>
	</section>
</main>
<?= Template::Footer() ?>
