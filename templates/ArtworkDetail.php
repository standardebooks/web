<h1><?= Formatter::ToPlainText($artwork->Name) ?></h1>
<picture>
	<a href="<?= $artwork->ImageUrl ?>"><img src="<?= $artwork->ThumbUrl ?>" property="schema:image"/></a>
</picture>
<h2>Metadata</h2>
<p>Title: <?= Formatter::ToPlainText($artwork->Name) ?></p>
<p>Artist: <?= Formatter::ToPlainText($artwork->Artist->Name) ?></p>
<p>Completed Year: <?= $artwork->CompletedYear ?><? if($artwork->CompletedYearIsCirca){ ?> (circa)<? } ?></p>
<p>Uploaded: <?= $artwork->Created->format('c') ?></p>
<p>Status: <?= Formatter::ToPlainText($artwork->Status) ?></p>
<p>Tags:</p>
<ul class="tags"><? foreach($artwork->ArtworkTags as $tag){ ?><li><a href="<?= $tag->Url ?>"><?= Formatter::ToPlainText($tag->Name) ?></a></li><? } ?></ul>
<h2>PD Proof</h2>
<aside class="tip">
	<p>PD proof must take the form of:</p>
	<ul>
		<li>Link to an approved museum page</li>
	</ul>
	<p>or <strong>all</strong> of the following:
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
