<?

$artworks = $artworks ?? [];
?>
<ol class="artwork-list list">
<? foreach($artworks as $artwork){ ?>
	<li>
		<div class="thumbnail-container">
			<a href="/artworks/<?= $artwork->Slug ?>">
				<picture>
					<img src="<?= $artwork->ThumbUrl ?>" property="schema:image"/>
				</picture>
			</a>
		</div>
		<p>Title: <a href="/artworks/<?= $artwork->Slug ?>" property="schema:name"><?= Formatter::ToPlainText($artwork->Name) ?></a></p>
		<p>Artist: <span class="author" typeof="schema:Person" property="schema:name"><?= Formatter::ToPlainText($artwork->Artist->Name) ?></span></p>
		<div>
			<p>Year completed: <? if ($artwork->CompletedYear === null){ ?>(unknown)<? }else{ ?><?= $artwork->CompletedYear ?><? if($artwork->CompletedYearIsCirca){ ?> (circa)<? } ?><? } ?></p>
		</div>
	</li>
<? } ?>
</ol>
