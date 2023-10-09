<?

$artworks = $artworks ?? [];
$useAdminUrl = $useAdminUrl ?? false;
?>
<ol class="artwork-list list">
<? foreach($artworks as $artwork){ ?>
	<? if($useAdminUrl){ ?>
		<? $url = '/admin/artworks/' . $artwork->ArtworkId; ?>
	<? }else{ ?>
		<? $url = $artwork->Url; ?>
	<? } ?>
	<li class="<?= $artwork->Status ?>">
		<div class="thumbnail-container">
			<a href="<?= $url ?>">
				<picture>
					<img src="<?= $artwork->ThumbUrl ?>" property="schema:image"/>
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
			<? if(count($artwork->ArtworkTags) > 0){ ?>
			<p>Tags: <ul class="tags"><? foreach($artwork->ArtworkTags as $tag){ ?><li><a href="<?= $tag->Url ?>"><?= Formatter::ToPlainText($tag->Name) ?></a></li><? } ?></ul></p>
			<? } ?>
		</div>
	</li>
<? } ?>
</ol>
