<?
$collections = Collection::GetAll();
$collectionsByLetter = [];

// Group collections by the first letter of their sort name.
foreach($collections as $collection){
	$letter = strtolower(substr(Formatter::RemoveDiacritics($collection->GetSortedName()), 0, 1));
	if($letter != ''){
		$collectionsByLetter[$letter][] = $collection;
	}
}

?><?= Template::Header(title: 'Ebook Collections', description: 'Browse collections of Standard Ebooks.') ?>
<main>
	<section class="has-hero columns-layout">
		<h1>Ebook Collections</h1>
		<picture data-caption="Still Life with Books and a Violin. Jan Davidszoon de Heem, 1625">
			<source srcset="/images/still-life-with-books@2x.avif 2x, /images/still-life-with-books.avif 1x" type="image/avif"/>
			<source srcset="/images/still-life-with-books@2x.jpg 2x, /images/still-life-with-books.jpg 1x" type="image/jpeg"/>
			<img src="/images/still-life-with-books@2x.jpg" alt="A pile of moldering books lying on a table."/>
		</picture>
		<nav aria-label="Collections by letter">
			<ul>
				<? foreach(range('a', 'z') as $letter){ ?>
					<li><? if(isset($collectionsByLetter[$letter])){ ?><a href="#<?= $letter ?>"><? } ?><?= $letter ?><? if(isset($collectionsByLetter[$letter])){ ?></a><? } ?></li>
				<? } ?>
			</ul>
		</nav>
		<? foreach($collectionsByLetter as $letter => $letterCollections){ ?>
			<section id="<?= $letter ?>">
				<h2><?= Formatter::EscapeHtml(strtoupper($letter)) ?><a class="heading-permalink" href="#<?= $letter ?>" aria-label="Permalink"></a></h2>
				<ul>
					<? foreach($letterCollections as $collection){ ?>
						<li>
							<p><a href="<?= $collection->Url ?>"><?= Formatter::EscapeHtml($collection->Name) ?></a></p>
						</li>
					<? } ?>
				</ul>
			</section>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
