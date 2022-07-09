<?
require_once('Core.php');

$ex = null;

if(isset($_SERVER['PHP_AUTH_USER'])){
	$ex = new Exceptions\InvalidPatronException();
}

$files = glob(WEB_ROOT . '/patrons-circle/downloads/*.zip');
rsort($files);

$years = [];

foreach($files as $file){
	$obj = new stdClass();
	$date = new DateTime(str_replace('se-ebooks-', '', basename($file, '.zip')) . '-01');
	$updated = new DateTime('@' . filemtime($file));
	$obj->Month = $date->format('F');
	$obj->Url = '/patrons-circle/downloads/' . basename($file);
	$obj->Updated = $updated->format('M i');

	if($updated->format('Y') != gmdate('Y')){
		$obj->Updated = $obj->Updated . $updated->format(', Y');
	}

	$year = $date->format('Y');

	if(!isset($years[$year])){
		$years[$year] = [];
	}

	$years[$year][] = $obj;
}

?><?= Template::Header(['title' => 'Bulk Ebook Download', 'highlight' => '', 'description' => 'Download zip files containing all of the Standard Ebooks released in a given month.']) ?>
<main>
	<section class="narrow bulk-downloads has-hero">
		<h1>Bulk Ebook Download</h1>
		<picture>
			<source srcset="/images/the-shop-of-the-bookdealer@2x.avif 2x, /images/the-shop-of-the-bookdealer.avif 1x" type="image/avif"/>
			<source srcset="/images/the-shop-of-the-bookdealer@2x.jpg 2x, /images/the-shop-of-the-bookdealer.jpg 1x" type="image/jpg"/>
			<img src="/images/the-shop-of-the-bookdealer@2x.jpg" alt="A gentleman in regency-era dress buys books from a bookseller."/>
		</picture>
		<? if($ex !== null){ ?>
		<ul class="message error">
			<li>
				<p><?= Formatter::ToPlainText($ex->getMessage()) ?></p>
			</li>
		</ul>
		<? } ?>
		<p><a href="/about#patrons-circle">Patrons circle members</a> can download zip files containing all of the ebooks that were released in a given month of Standard Ebooks history. You can <a href="/donate#patrons-circle">join the Patrons Circle</a> with a small donation in support of our continuing mission to create free, beautiful digital literature.</p>
		<p>These zip files contain each ebook in every format we offer, and are updated once daily with the latest versions of each ebook.</p>
		<p>If you’re a Patrons Circle member, when prompted enter your email address and leave the password blank to download these files.</p>
		<ul class="download-list">
		<? foreach($years as $year => $items){ ?>
		<li>
			<section>
				<h2><?= Formatter::ToPlainText($year) ?></h2>
				<ul>
					<? foreach($items as $item){ ?>
					<li><p><a download="" href="<?= Formatter::ToPlainText($item->Url) ?>"><?= Formatter::ToPlainText($item->Month) ?></a> <i>(Updated <?= Formatter::ToPlainText($obj->Updated) ?>)</i></p></li>
					<? } ?>
				</ul>
			</section>
		</li>
		<? } ?>
		</ul>
	</section>
</main>
<?= Template::Footer() ?>
