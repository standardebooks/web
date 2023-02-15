<?
require_once('Core.php');

use function Safe\apcu_fetch;

$collection = Library::GetEbookCollections();

?><?= Template::Header(['title' => 'Ebook Collections', 'highlight' => '', 'description' => 'Browse collections of Standard Ebooks.']) ?>
<main>
	<section class="narrow has-hero">
		<h1>Ebook Collections</h1>
		<picture data-caption="Still Life with Books. Jan Davidsz. de Heem, 1625">
			<source srcset="/images/still-life-with-books@2x.avif 2x, /images/still-life-with-books.avif 1x" type="image/avif"/>
			<source srcset="/images/still-life-with-books@2x.jpg 2x, /images/still-life-with-books.jpg 1x" type="image/jpg"/>
			<img src="/images/still-life-with-books@2x.jpg" alt="A gentleman in regency-era dress buys books from a bookseller."/>
		</picture>
		<?= Template::CollectionTable(['collections' => $collection]); ?>
	</section>
</main>
<?= Template::Footer() ?>
