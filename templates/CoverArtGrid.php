<?

$coverArtList = $coverArtList ?? [];
?>
<ol class="cover-art-list list">
<? foreach($coverArtList as $coverArt){ ?>
	<li>
		<div class="thumbnail-container">
			<picture>
				<img src="<?= $coverArt->ImageUrl ?>" property="schema:image"/>
			</picture>
		</div>
		<p>Title: <span property="schema:name"><?= Formatter::ToPlainText($coverArt->Title) ?></span></p>
		<p>Artist: <span class="author" typeof="schema:Person" property="schema:name"><?= Formatter::ToPlainText($coverArt->ArtistName) ?></span></p>
		<div>
			<p>Year: <?= $coverArt->Year ?></p>
			<? if ($coverArt->Status == COVER_ART_STATUS_UNAVAILABLE) { ?>
				<p>Status: Unavailable</p>
				<p>Ebook: <a href="<?= $coverArt->Ebook->Url ?>" property="schema:url"><span property="schema:name"><?= Formatter::ToPlainText($coverArt->Ebook->Title) ?></span></a></p>
			<? } ?>
		</div>
	</li>
<? } ?>
</ol>
