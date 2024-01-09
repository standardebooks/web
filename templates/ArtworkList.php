<?
$artworks = $artworks ?? [];
$showStatus = $showStatus ?? false;
?>
<ol class="artwork-list list">
<? foreach($artworks as $artwork){ ?>
	<li class="<?= str_replace('_', '-', $artwork->Status) ?>">
		<div class="thumbnail-container">
			<a href="<?= $artwork->Url ?>">
				<picture>
					<source srcset="<?= $artwork->Thumb2xUrl ?> 2x, <?= $artwork->ThumbUrl ?> 1x" type="image/jpg"/>
					<img src="<?= $artwork->ThumbUrl ?>" alt="" property="schema:image"/>
				</picture>
			</a>
		</div>
		<p><a href="<?= $artwork->Url ?>" property="schema:name"><?= Formatter::ToPlainText($artwork->Name) ?></a></p>
		<p>
			<span class="author" typeof="schema:Person" property="schema:name"><?= Formatter::ToPlainText($artwork->Artist->Name) ?></span>
			<? if(sizeof($artwork->Artist->AlternateSpellings) > 0){ ?>(<abbr>AKA</abbr> <span class="author" typeof="schema:Person" property="schema:name"><?= implode('</span>, <span class="author" typeof="schema:Person" property="schema:name">', array_map('Formatter::ToPlainText', $artwork->Artist->AlternateSpellings)) ?></span>)<? } ?>
		</p>
		<div>
			<p>Year completed: <? if($artwork->CompletedYear === null){ ?>Unknown<? }else{ ?><? if($artwork->CompletedYearIsCirca){ ?>Circa <? } ?><?= $artwork->CompletedYear ?><? } ?></p>
			<? if($showStatus || $artwork->Status == COVER_ARTWORK_STATUS_IN_USE){ ?><p>Status: <?= Template::ArtworkStatus(['artwork' => $artwork]) ?></p><? } ?>
			<? if(count($artwork->Tags) > 0){ ?>
			<p>Tags:</p>
			<ul class="tags"><? foreach($artwork->Tags as $tag){ ?><li><a href="<?= $tag->Url ?>"><?= Formatter::ToPlainText($tag->Name) ?></a></li><? } ?></ul>
			<? } ?>
		</div>
	</li>
<? } ?>
</ol>
