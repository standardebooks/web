<h1><?= Formatter::ToPlainText($artwork->Name) ?></h1>
<a href="<?= $artwork->ImageUrl ?>">
	<picture>
		<img src="<?= $artwork->ThumbUrl ?>" property="schema:image"/>
	</picture>
</a>
<h2>Metadata</h2>
<table class="artwork-metadata">
	<tr>
		<td>Title</td>
		<td><?= Formatter::ToPlainText($artwork->Name) ?></td>
	</tr>
	<tr>
		<td>Artist</td>
		<td><?= Formatter::ToPlainText($artwork->Artist->Name) ?><? if($artwork->Artist->DeathYear !== null){ ?>, <abbr title="deceased">d.</abbr> <?= $artwork->Artist->DeathYear ?><? } ?></td>
	</tr>
	<tr>
		<td>Year completed</td>
		<td><? if ($artwork->CompletedYear === null){ ?>(unknown)<? }else{ ?><?= $artwork->CompletedYear ?><? if($artwork->CompletedYearIsCirca){ ?> (circa)<? } ?><? } ?></td>
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
		<td><? if($artwork->Status === "approved"){ ?>Approved<? }else if($artwork->Status === "in_use"){ ?>In use by <a href="<?= $artwork->Ebook->Url ?>" property="schema:url"><span property="schema:name"><?= Formatter::ToPlainText($artwork->Ebook->Title) ?></span></a><? }else{ ?><?= Formatter::ToPlainText($artwork->Status) ?><? } ?></td>
	</tr>
	<tr>
		<td>Tags</td>
		<td><ul class="tags"><? foreach($artwork->ArtworkTags as $tag){ ?><li><a href="<?= $tag->Url ?>"><?= Formatter::ToPlainText($tag->Name) ?></a></li><? } ?></ul></td>
	</tr>
</table>
<h2>PD Proof</h2>
<aside class="tip">
	<p>PD proof must take the form of:</p>
	<ul>
		<li>Link to an approved museum page</li>
	</ul>
	<p>or <strong>all</strong> of the following:</p>
	<ol>
		<li>Year book was published</li>
		<li>Link to direct page scan of artwork (not just the start of the book, the direct page)</li>
		<li>Link to direct page scan of page mentioning book publication year (not just the start of the book, the direct page)</li>
		<li>Link to direct page scan of book copyright/rights statement page (may be the same as the publication year page but not always)</li>
	</ol>
</aside>
<h3>Museum page</h3>
<ul>
	<li>Link to an approved museum page: <? if($artwork->MuseumPage !== null){ ?> <a href="<?= Formatter::ToPlainText($artwork->MuseumPage) ?>">Link</a><? }else{ ?>(not provided)<? } ?></li>
</ul>
<h3>Links to scans</h3>
<ol>
	<li>Year book was published: <? if($artwork->PublicationYear !== null){ ?><?= $artwork->PublicationYear ?><? }else{ ?>(not provided)<? } ?></li>
	<li>Link to direct page scan of artwork: <? if($artwork->ArtworkPage !== null){ ?><a href="<?= Formatter::ToPlainText($artwork->ArtworkPage) ?>">Link</a><? }else{ ?>(not provided)<? } ?></li>
	<li>Link to direct page scan of page mentioning book publication year: <? if($artwork->PublicationYearPage !== null){ ?><a href="<?= Formatter::ToPlainText($artwork->PublicationYearPage) ?>">Link</a><? }else{ ?>(not provided)<? } ?></li>
	<li>Link to direct page scan of book copyright/rights statement page: <? if($artwork->CopyrightPage !== null){ ?><a href="<?= Formatter::ToPlainText($artwork->CopyrightPage) ?>">Link</a><? }else{ ?>(not provided)<? } ?></li>
</ol>
