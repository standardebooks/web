<?
$artwork = $artwork ?? null;

if($artwork === null){
	return;
}
?>

<h1><?= Formatter::ToPlainText($artwork->Name) ?></h1>

<a href="<?= $artwork->ImageUrl ?>">
	<picture>
		<source srcset="<?= $artwork->Thumb2xUrl ?> 2x, <?= $artwork->ThumbUrl ?> 1x" type="image/jpg"/>
		<img src="<?= $artwork->ThumbUrl ?>" alt="" property="schema:image"/>
	</picture>
</a>

<?= Template::ImageCopyrightNotice() ?>

<h2>Metadata</h2>
<table class="artwork-metadata">
	<tr>
		<td>Title</td>
		<td><?= Formatter::ToPlainText($artwork->Name) ?></td>
	</tr>
	<tr>
		<td>Artist</td>
		<td>
			<?= Formatter::ToPlainText($artwork->Artist->Name) ?><? if(sizeof($artwork->Artist->AlternateSpellings) > 0){ ?> (<abbr>AKA</abbr> <span class="author" typeof="schema:Person" property="schema:name"><?= implode('</span>, <span class="author" typeof="schema:Person" property="schema:name">', array_map('Formatter::ToPlainText', $artwork->Artist->AlternateSpellings)) ?></span>)<? } ?><? if($artwork->Artist->DeathYear !== null){ ?>, <abbr title="deceased">d.</abbr> <?= $artwork->Artist->DeathYear ?><? } ?>
		</td>
	</tr>
	<tr>
		<td>Year completed</td>
		<td><? if($artwork->CompletedYear === null){ ?>(unknown)<? }else{ ?><?= $artwork->CompletedYear ?><? if($artwork->CompletedYearIsCirca){ ?> (circa)<? } ?><? } ?></td>
	</tr>
	<tr>
		<td>File size</td>
		<td><?= $artwork->ImageSize ?></td>
	</tr>
	<tr>
		<td>Uploaded</td>
		<td><?= $artwork->Created->format('F j, Y g:i a') ?></td>
	</tr>
	<tr>
		<td>Status</td>
		<td><?= Template::ArtworkStatus(['artwork' => $artwork]) ?></td>
	</tr>
	<tr>
		<td>Tags</td>
		<td><ul class="tags"><? foreach($artwork->Tags as $tag){ ?><li><a href="<?= $tag->Url ?>"><?= Formatter::ToPlainText($tag->Name) ?></a></li><? } ?></ul></td>
	</tr>
</table>

<h2>U.S. public domain proof</h2>
<? if($artwork->MuseumUrl !== null){ ?>
	<h3>Museum page</h3>
	<p><a href="<?= Formatter::ToPlainText($artwork->MuseumUrl) ?>"><?= Formatter::ToPlainText($artwork->MuseumUrl) ?></a></p>
	<? if($artwork->Museum !== null){ ?>
	<figure class="corrected full">
		<p>Approved museum: <?= Formatter::ToPlainText($artwork->Museum->Name) ?> <code>(<?= Formatter::ToPlainText($artwork->Museum->Domain) ?>)</code></p>
	</figure>
	<? }else{ ?>
	<figure class="wrong full">
		<p>Not an approved museum.</p>
	</figure>
	<? } ?>
<? } ?>

<? if($artwork->PublicationYear !== null){ ?>
	<h3>Page scans</h3>
	<ul>
		<li>Year book was published: <? if($artwork->PublicationYear !== null){ ?><?= $artwork->PublicationYear ?><? }else{ ?><i>Not provided</i><? } ?></li>
		<li>Was book published in the U.S.: <? if($artwork->IsPublishedInUs){ ?>Yes<? }else{ ?>No<? } ?></li>
		<li>Page scan of book publication year: <? if($artwork->PublicationYearPageUrl !== null){ ?><a href="<?= Formatter::ToPlainText($artwork->PublicationYearPageUrl) ?>">Link</a><? }else{ ?><i>Not provided</i><? } ?></li>
		<li>Page scan of rights statement page: <? if($artwork->CopyrightPageUrl !== null){ ?><a href="<?= Formatter::ToPlainText($artwork->CopyrightPageUrl) ?>">Link</a><? }else{ ?><i>Not provided</i><? } ?></li>
		<li>Page scan of artwork: <? if($artwork->ArtworkPageUrl !== null){ ?><a href="<?= Formatter::ToPlainText($artwork->ArtworkPageUrl) ?>">Link</a><? }else{ ?><i>Not provided</i><? } ?></li>
	</ul>
<? } ?>

<? if($artwork->Exception !== null){ ?>
	<h3>Special public domain exception</h3>
	<?= Formatter::EscapeMarkdown($artwork->Exception) ?>
<? } ?>
