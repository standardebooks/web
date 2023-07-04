<?

$artworks = $artworks ?? [];
?>
<ol class="artwork-list list">
<? foreach($artworks as $artwork){ ?>
	<li class="<?= $artwork->Status ?>">
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
			<p>Status: <? if($artwork->Status === "approved"){ ?>Approved<? }else if($artwork->Status === "in_use"){ ?>In use by <a href="<?= $artwork->Ebook->Url ?>" property="schema:url"><span property="schema:name"><?= Formatter::ToPlainText($artwork->Ebook->Title) ?></span></a><? } ?></p>
			<? if(count($artwork->ArtworkTags) > 0){ ?>
			<p>Tags: <ul class="tags"><? foreach($artwork->ArtworkTags as $tag){ ?><li><a href="<?= $tag->Url ?>"><?= Formatter::ToPlainText($tag->Name) ?></a></li><? } ?></ul></p>
			<? } ?>
		</div>
	</li>
<? } ?>
</ol>
