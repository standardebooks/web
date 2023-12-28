<?

try{
	$artistUrlName = HttpInput::Str(GET, 'artist') ?? '';
	$artworkUrlName = HttpInput::Str(GET, 'artwork') ?? '';

	$artwork = Artwork::GetByUrlAndIsApproved($artistUrlName, $artworkUrlName);
}
catch(Exceptions\ArtworkNotFoundException){
	Template::Emit404();
}

?><?= Template::Header(['title' => $artwork->Name, 'artwork' => true]) ?>
<main class="artworks">
	<section class="narrow">
		<?= Template::ArtworkDetail(['artwork' => $artwork]) ?>
	</section>
</main>
<?= Template::Footer() ?>
