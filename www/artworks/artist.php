<?
$artistUrlName = '';

try{
	$artistUrlName = trim(HttpInput::Str(GET, 'artist-url-name') ?? '');

	if($artistUrlName == ''){
		throw new Exceptions\ArtistNotFoundException();
	}

	$artworks = Library::GetArtworksByArtist($artistUrlName);

	if(sizeof($artworks) == 0){
		throw new Exceptions\ArtistNotFoundException();
	}

	$artistName =  $artworks[0]->Artist->Name;
}
catch(Exceptions\ArtistNotFoundException){
	Template::Emit404();
}
?><?= Template::Header(['title' => 'Artwork by ' . $artistName, 'artwork' => true]) ?>
<main class="artworks">
	<section class="narrow">
		<h1>Artwork by <?= Formatter::EscapeHtml($artistName) ?></h1>

		<?= Template::ImageCopyrightNotice() ?>

		<?= Template::ArtworkList(['artworks' => $artworks]) ?>
	</section>
</main>
<?= Template::Footer() ?>
