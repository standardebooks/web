<?
require_once('Core.php');

$artistUrlName = HttpInput::Str(GET, 'artist');
$artworkUrlName = HttpInput::Str(GET, 'artwork');
$slug = '/' . $artistUrlName . '/' . $artworkUrlName;

try{
	$artwork = Library::GetArtworkBySlug($slug);
}
catch(Safe\Exceptions\ApcuException){
	Template::Emit404();
}

?><?= Template::Header(['title' => $artwork->Name]) ?>
<main class="artworks">
	<section>
		<?= Template::ArtworkDetail(['artwork' => $artwork]) ?>
	</section>
</main>
<?= Template::Footer() ?>
