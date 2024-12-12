<?

use function Safe\preg_replace;

/** @var string $identifier Passed from script this is included from. */
$ebook = null;

try{
	$ebook = Ebook::GetByIdentifier($identifier);
}
catch(Exceptions\EbookNotFoundException){
	Template::Emit404();
}
?><?= Template::Header(
	[
		'title' => strip_tags($ebook->TitleWithCreditsHtml),
		'css' => ['/css/ebook-placeholder.css'],
		'highlight' => 'ebooks',
		'canonicalUrl' => SITE_URL . $ebook->Url
	])
?>
<main>
	<article class="ebook ebook-placeholder" typeof="schema:Book" about="<?= $ebook->Url ?>">
		<header>
			<hgroup>
				<h1 property="schema:name"><?= Formatter::EscapeHtml($ebook->Title) ?></h1>
				<? foreach($ebook->Authors as $author){ ?>
					<?
						/* We include the `resource` attr here because we can have multiple authors, and in that case their href URLs will link to their combined corpus.

						For example, William Wordsworth & Samuel Coleridge will both link to `/ebooks/william-wordsworth_samuel-taylor-coleridge`.

						But, each author is an individual, so we have to differentiate them in RDFa with `resource`.
					*/ ?>
					<? if($author->Name != 'Anonymous'){ ?>
						<h2>
							<a property="schema:author" typeof="schema:Person" href="<?= Formatter::EscapeHtml($ebook->AuthorsUrl) ?>" resource="<?= '/ebooks/' . $author->UrlName ?>">
								<span property="schema:name"><?= Formatter::EscapeHtml($author->Name) ?></span>
								<meta property="schema:url" content="<?= SITE_URL . Formatter::EscapeHtml($ebook->AuthorsUrl) ?>"/>
								<? if($author->NacoafUrl){ ?>
									<meta property="schema:sameAs" content="<?= Formatter::EscapeHtml($author->NacoafUrl) ?>"/>
								<? } ?>
								<? if($author->WikipediaUrl){ ?>
									<meta property="schema:sameAs" content="<?= Formatter::EscapeHtml($author->WikipediaUrl) ?>"/>
								<? } ?>
							</a>
						</h2>
					<? } ?>
				<? } ?>
			</hgroup>
		</header>

		<aside id="reading-ease">
			<? if($ebook->ContributorsHtml != ''){ ?>
				<p><?= $ebook->ContributorsHtml ?></p>
			<? } ?>
			<? if(sizeof($ebook->CollectionMemberships) > 0){ ?>
				<? foreach($ebook->CollectionMemberships as $collectionMembership){ ?>
					<? $collection = $collectionMembership->Collection; ?>
					<? $sequenceNumber = $collectionMembership->SequenceNumber; ?>
					<p>
						<? if($sequenceNumber !== null){ ?>№ <?= number_format($sequenceNumber) ?> in the<? }else{ ?>Part of the<? } ?> <a href="<?= $collection->Url ?>" property="schema:isPartOf"><?= Formatter::EscapeHtml(preg_replace('/^The /ius', '', (string)$collection->Name)) ?></a>
						<? if($collection->Type !== null){ ?>
							<? if(substr_compare(mb_strtolower($collection->Name), mb_strtolower($collection->Type->value), -strlen(mb_strtolower($collection->Type->value))) !== 0){ ?>
								<?= $collection->Type->value ?>.
							<? } ?>
						<? }else{ ?>
							collection.
						<? } ?>
					</p>
				<? } ?>
			<? } ?>
		</aside>

		<section class="placeholder-details">
			<? if($ebook->EbookPlaceholder->IsPublicDomain){ ?>
				<p>We don’t have this ebook in our catalog yet.</p>
				<p>You can <a href="/donate#sponsor-an-ebook">sponsor the production of this ebook</a> and we’ll get working on it immediately!</p>
			<? }elseif($ebook->EbookPlaceholder->YearPublished !== null){ ?>
				<p>This book was published in <?= $ebook->EbookPlaceholder->YearPublished ?>, and will therefore enter the U.S. public domain on <b>January 1, <?= $ebook->EbookPlaceholder->YearPublished + 96 ?>.</b></p>
				<p>We can’t work on it any earlier than that.</p>
			<? }else{ ?>
				<p>This book is not yet in the U.S. public domain. We can’t offer it until it is.</p>
			<? } ?>
		</section>
	</article>
</main>
<?= Template::Footer() ?>
