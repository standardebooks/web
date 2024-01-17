<?
$collections = Library::GetEbookCollections();

?><?= Template::Header(['title' => 'Ebook Collections', 'highlight' => '', 'description' => 'Browse collections of Standard Ebooks.']) ?>
<main>
	<section class="narrow has-hero">
		<h1>Ebook Collections</h1>
		<picture data-caption="Still Life with Books and a Violin. Jan Davidszoon de Heem, 1625">
			<source srcset="/images/still-life-with-books@2x.avif 2x, /images/still-life-with-books.avif 1x" type="image/avif"/>
			<source srcset="/images/still-life-with-books@2x.jpg 2x, /images/still-life-with-books.jpg 1x" type="image/jpg"/>
			<img src="/images/still-life-with-books@2x.jpg" alt="A pile of moldering books lying on a table."/>
		</picture>
		<ul>
			<? foreach($collections as $collection){ ?>
			<li>
				<p><a href="<?= $collection->Url ?>"><?= Formatter::EscapeHtml($collection->Name) ?></a></p>
			</li>
			<? } ?>
		</ul>
	</section>
</main>
<?= Template::Footer() ?>
