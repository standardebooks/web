<?
try{
	$artworks = Library::GetArtworksByArtist(HttpInput::Str(GET, 'artist-url-name'));

	if(sizeof($artworks) == 0){
		throw new Exceptions\ArtistNotFoundException();
	}
}
catch(Exceptions\ArtistNotFoundException){
	Template::Emit404();
}
?><?= Template::Header(['title' => 'Artwork by ' . $artworks[0]->Artist->Name, 'artwork' => true]) ?>
<main class="artworks">
	<section class="narrow">
		<h1>Artwork by <?= Formatter::EscapeHtml($artworks[0]->Artist->Name) ?></h1>

		<?= Template::ImageCopyrightNotice() ?>

		<?= Template::ArtworkList(['artworks' => $artworks]) ?>
	</section>
</main>
<?= Template::Footer() ?>
