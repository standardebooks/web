<?

$artworks = $artworks ?? [];
$useAdminUrl = $useAdminUrl ?? false;
?>
<ol class="artwork-list list">
<? foreach($artworks as $artwork){ ?>
	<? if($useAdminUrl){ ?>
		<? $url = $artwork->AdminUrl; ?>
	<? }else{ ?>
		<? $url = $artwork->Url; ?>
	<? } ?>
	<li class="<?= $artwork->Status ?>">
		<div class="thumbnail-container">
			<a href="<?= $url ?>">
				<picture>
					<source srcset="<?= $artwork->Thumb2xUrl ?> 2x, <?= $artwork->ThumbUrl ?> 1x" type="image/jpg"/>
					<img src="<?= $artwork->ThumbUrl ?>" alt="" property="schema:image"/>
				</picture>
			</a>
		</div>
		<p><a href="<?= $url ?>" property="schema:name"><?= Formatter::ToPlainText($artwork->Name) ?></a></p>
		<p>
			<span class="author" typeof="schema:Person" property="schema:name"><?= Formatter::ToPlainText($artwork->Artist->Name) ?></span>
			<? if(sizeof($artwork->Artist->AlternateSpellings) > 0){ ?>(<abbr>AKA</abbr> <span class="author" typeof="schema:Person" property="schema:name"><?= implode('</span>, <span class="author" typeof="schema:Person" property="schema:name">', array_map('Formatter::ToPlainText', $artwork->Artist->AlternateSpellings)) ?></span>)<? } ?>
		</p>
		<div>
			<p>Year completed: <? if($artwork->CompletedYear === null){ ?>(unknown)<? }else{ ?><?= $artwork->CompletedYear ?><? if($artwork->CompletedYearIsCirca){ ?> (circa)<? } ?><? } ?></p>
			<p>Status: <?= Template::ArtworkStatus(['artwork' => $artwork]) ?></p>
			<? if(count($artwork->Tags) > 0){ ?>
			<p>Tags:</p>
			<ul class="tags"><? foreach($artwork->Tags as $tag){ ?><li><a href="<?= $tag->Url ?>"><?= Formatter::ToPlainText($tag->Name) ?></a></li><? } ?></ul>
			<? } ?>
		</div>
	</li>
<? } ?>
</ol>
