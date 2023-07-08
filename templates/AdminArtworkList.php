<?

$artworks = $artworks ?? [];
?>
<ol class="artwork-list list">
<? foreach($artworks as $artwork){ ?>
	<li>
		<div class="thumbnail-container">
			<picture>
				<a href="/admin/artworks/<?= $artwork->ArtworkId ?>"><img src="<?= $artwork->ThumbUrl ?>" property="schema:image"/></a>
			</picture>
		</div>
		<p>Title: <a href="/admin/artworks/<?= $artwork->ArtworkId ?>" property="schema:name"><?= Formatter::ToPlainText($artwork->Name) ?></a></p>
		<p>Artist: <span class="author" typeof="schema:Person" property="schema:name"><?= Formatter::ToPlainText($artwork->Artist->Name) ?></span></p>
		<div>
			<p>Year completed: <? if ($artwork->CompletedYear === null){ ?>(unknown)<? }else{ ?><?= $artwork->CompletedYear ?><? if($artwork->CompletedYearIsCirca){ ?> (circa)<? } ?><? } ?></p>
		</div>
	</li>
<? } ?>
</ol>
