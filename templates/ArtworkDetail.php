<?
$artwork = $artwork ?? null;
$isAdminView = $isAdminView ?? false;

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
		<td><i><?= Formatter::ToPlainText($artwork->Name) ?></i></td>
	</tr>
	<tr>
		<td>Artist</td>
		<td>
			<?= Formatter::ToPlainText($artwork->Artist->Name) ?><? if(sizeof($artwork->Artist->AlternateSpellings) > 0){ ?> (<abbr>AKA</abbr> <span class="author" typeof="schema:Person" property="schema:name"><?= implode('</span>, <span class="author" typeof="schema:Person" property="schema:name">', array_map('Formatter::ToPlainText', $artwork->Artist->AlternateSpellings)) ?></span>)<? } ?><? if($artwork->Artist->DeathYear !== null){ ?> (<abbr>d.</abbr> <?= $artwork->Artist->DeathYear ?>)<? } ?>
		</td>
	</tr>
	<tr>
		<td>Year completed</td>
		<td><? if($artwork->CompletedYear === null){ ?>Unknown<? }else{ ?><? if($artwork->CompletedYearIsCirca){ ?>Circa <? } ?><?= $artwork->CompletedYear ?><? } ?></td>
	</tr>
	<tr>
		<td>Tags</td>
		<td><ul class="tags"><? foreach($artwork->Tags as $tag){ ?><li><a href="<?= $tag->Url ?>"><?= Formatter::ToPlainText($tag->Name) ?></a></li><? } ?></ul></td>
	</tr>
	<tr>
		<td>Dimensions</td>
		<td><?= $artwork->Dimensions ?></td>
	</tr>
	<tr>
		<td>Status</td>
		<td><?= Template::ArtworkStatus(['artwork' => $artwork]) ?></td>
	</tr>
	<? if($isAdminView){ ?>
	<tr>
		<td>Submitted by</td>
		<td><? if($artwork->SubmitterUserId === null){ ?>Anonymous<? }else{ ?><a href="mailto:<?= Formatter::ToPlainText($artwork->Submitter->Email) ?>"><? if($artwork->Submitter->Name !== null){ ?> <?= Formatter::ToPlainText($artwork->Submitter->Name) ?><? }else{ ?><?= Formatter::ToPlainText($artwork->Submitter->Email) ?><? } ?></a><? } ?></td>
	</tr>
	<? } ?>
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
		<li>Page scan of book publication year: <? if($artwork->PublicationYearPageUrl !== null){ ?><a href="<?= Formatter::ToPlainText($artwork->PublicationYearPageUrl) ?>">Link</a><? }else{ ?><i>Not provided</i><? } ?></li>
		<li>Page scan of rights statement: <? if($artwork->CopyrightPageUrl !== null){ ?><a href="<?= Formatter::ToPlainText($artwork->CopyrightPageUrl) ?>">Link</a><? }else{ ?><i>Not provided</i><? } ?></li>
		<li>Page scan of artwork: <? if($artwork->ArtworkPageUrl !== null){ ?><a href="<?= Formatter::ToPlainText($artwork->ArtworkPageUrl) ?>">Link</a><? }else{ ?><i>Not provided</i><? } ?></li>
	</ul>
<? } ?>

<? if($artwork->Exception !== null){ ?>
	<h3>Special public domain exception</h3>
	<?= Formatter::EscapeMarkdown($artwork->Exception) ?>
<? } ?>
