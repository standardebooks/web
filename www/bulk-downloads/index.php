<?
require_once('Core.php');

$canDownload = false;
if($GLOBALS['User'] !== null && $GLOBALS['User']->Benefits->CanBulkDownload){
	$canDownload = true;
}

?><?= Template::Header(['title' => 'Bulk Ebook Downloads', 'highlight' => '', 'description' => 'Download zip files containing all of the Standard Ebooks released in a given month.']) ?>
<main>
	<section class="narrow has-hero">
		<h1>Bulk Ebook Downloads</h1>
		<picture data-caption="The Shop of the Bookdealer Pieter Meijer Warnars. Johannes Jelgerhuis, 1820">
			<source srcset="/images/the-shop-of-the-bookdealer@2x.avif 2x, /images/the-shop-of-the-bookdealer.avif 1x" type="image/avif"/>
			<source srcset="/images/the-shop-of-the-bookdealer@2x.jpg 2x, /images/the-shop-of-the-bookdealer.jpg 1x" type="image/jpg"/>
			<img src="/images/the-shop-of-the-bookdealer@2x.jpg" alt="A gentleman in regency-era dress buys books from a bookseller."/>
		</picture>
		<p><a href="/about#patrons-circle">Patrons circle members</a> can download zip files containing all of the ebooks that were released in a given month of Standard Ebooks history.<? if(!$canDownload){ ?> You can <a href="/donate#patrons-circle">join the Patrons Circle</a> with a small donation in support of our continuing mission to create free, beautiful digital literature.<? } ?></p>
		<ul>
			<li>
				<p><a href="/bulk-downloads/subjects">Downloads by subject</a></p>
			</li>
			<li>
				<p><a href="/bulk-downloads/collections">Downloads by collection</a></p>
			</li>
			<li>
				<p><a href="/bulk-downloads/authors">Downloads by author</a></p>
			</li>
			<li>
				<p><a href="/bulk-downloads/months">Downloads by month</a></p>
			</li>
		</ul>
	</section>
</main>
<?= Template::Footer() ?>
