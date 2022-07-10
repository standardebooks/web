<?
require_once('Core.php');

use Safe\DateTime;
use function Safe\apcu_fetch;
use function Safe\filemtime;
use function Safe\filesize;
use function Safe\glob;
use function Safe\gmdate;
use function Safe\preg_match;
use function Safe\sort;
use function Safe\rsort;
use function Safe\usort;

$forbiddenException = null;

if(isset($_SERVER['PHP_AUTH_USER'])){
	// We get here if the user entered an invalid HTTP Basic Auth username,
	// and this page was served as the 401 page.
	$forbiddenException = new Exceptions\InvalidPatronException();
}

// Process ebooks by year
$years = [];

try{
	$years = apcu_fetch('bulk-downloads-years');
}
catch(Safe\Exceptions\ApcuException $ex){
	// Nothing in the cache, generate the files

	$files = glob(WEB_ROOT . '/bulk-downloads/months/*/*.zip');
	rsort($files);

	foreach($files as $file){
		$obj = new stdClass();
		$obj->Updated = new DateTime('@' . filemtime($file));
		$date = new DateTime();

		preg_match('/se-ebooks-(\d+-\d+)/ius', basename($file), $matches);
		if(sizeof($matches) == 2){
			$date = new DateTime($matches[1] . '-01');
		}

		// The type of zip is stored as a filesystem attribute
		$obj->Type = exec('attr -g se-ebook-type ' . escapeshellarg($file));
		if($obj->Type == 'epub-advanced'){
			$obj->Type = 'epub (advanced)';
		}

		$obj->Month = $date->format('Y-m');
		$obj->Url = '/bulk-downloads/months/' . $obj->Month . '/' . basename($file);
		$obj->Size = Formatter::ToFileSize(filesize($file));

		// The count of ebooks in each file is stored as a filesystem attribute
		$obj->Count = exec('attr -g se-ebook-count ' . escapeshellarg($file)) ?: null;
		if($obj->Count !== null){
			$obj->Count = intval($obj->Count);
		}

		$obj->UpdatedString = $obj->Updated->format('M j');
		if($obj->Updated->format('Y') != gmdate('Y')){
			$obj->UpdatedString = $obj->Updated->format('M j, Y');
		}

		$year = $date->format('Y');
		$month = $date->format('F');

		if(!isset($years[$year])){
			$years[$year] = [];
		}

		if(!isset($years[$year][$month])){
			$years[$year][$month] = [];
		}

		$years[$year][$month][] = $obj;
	}

	// Sort the downloads by filename extension
	foreach($years as $year => $months){
		foreach($months as $month => $items){
			usort($items, function($a, $b){ return $a->Type <=> $b->Type; });

			// We have to reassign it because the foreach created a clone of the array
			$years[$year][$month] = $items;
		}
	}

	apcu_store('bulk-downloads-years', $years, 43200); // 12 hours
}


$subjects = [];
// Process ebooks by subject
try{
	$subjects = apcu_fetch('bulk-downloads-subjects');
}
catch(Safe\Exceptions\ApcuException $ex){
	// Nothing in the cache, generate the files
	$files = glob(WEB_ROOT . '/bulk-downloads/subjects/*/*.zip');
	sort($files);

	foreach($files as $file){
		$obj = new stdClass();
		$obj->Url = '/bulk-downloads/' . basename($file);
		$obj->Size = Formatter::ToFileSize(filesize($file));
		$obj->Updated = new DateTime('@' . filemtime($file));

		// The count of ebooks in each file is stored as a filesystem attribute
		$obj->Count = exec('attr -g se-ebook-count ' . escapeshellarg($file)) ?: null;
		if($obj->Count !== null){
			$obj->Count = intval($obj->Count);
		}

		// The subject of the batch is stored as a filesystem attribute
		$obj->Subject = exec('attr -g se-subject ' . escapeshellarg($file)) ?: null;
		if($obj->Subject === null){
			$obj->Subject = str_replace('se-ebooks-', '', basename($file, '.zip'));
		}

		// The type of zip is stored as a filesystem attribute
		$obj->Type = exec('attr -g se-ebook-type ' . escapeshellarg($file));
		if($obj->Type == 'epub-advanced'){
			$obj->Type = 'epub (advanced)';
		}

		$obj->UpdatedString = $obj->Updated->format('M j');
		if($obj->Updated->format('Y') != gmdate('Y')){
			$obj->UpdatedString = $obj->Updated->format('M j, Y');
		}

		if(!isset($subjects[$obj->Subject])){
			$subjects[$obj->Subject] = [];
		}

		$subjects[$obj->Subject][] = $obj;
	}

	// Subjects downloads are already correctly sorted

	apcu_store('bulk-downloads-subjects', $subjects, 43200); // 12 hours
}

?><?= Template::Header(['title' => 'Bulk Ebook Downloads', 'highlight' => '', 'description' => 'Download zip files containing all of the Standard Ebooks released in a given month.']) ?>
<main>
	<section class="bulk-downloads has-hero">
		<h1>Bulk Ebook Downloads</h1>
		<picture>
			<source srcset="/images/the-shop-of-the-bookdealer@2x.avif 2x, /images/the-shop-of-the-bookdealer.avif 1x" type="image/avif"/>
			<source srcset="/images/the-shop-of-the-bookdealer@2x.jpg 2x, /images/the-shop-of-the-bookdealer.jpg 1x" type="image/jpg"/>
			<img src="/images/the-shop-of-the-bookdealer@2x.jpg" alt="A gentleman in regency-era dress buys books from a bookseller."/>
		</picture>
		<? if($forbiddenException !== null){ ?>
		<ul class="message error">
			<li>
				<p><?= Formatter::ToPlainText($forbiddenException->getMessage()) ?></p>
			</li>
		</ul>
		<? } ?>
		<p><a href="/about#patrons-circle">Patrons circle members</a> can download zip files containing all of the ebooks that were released in a given month of Standard Ebooks history. You can <a href="/donate#patrons-circle">join the Patrons Circle</a> with a small donation in support of our continuing mission to create free, beautiful digital literature.</p>
		<p>These zip files contain each ebook in every format we offer, and are updated once daily with the latest versions of each ebook.</p>
		<p>If you’re a Patrons Circle member, when prompted enter your email address and leave the password field blank to download these files.</p>

		<section id="downloads-by-subject">
			<h2>Downloads by subject</h2>
			<table class="download-list">
				<thead>
					<tr class="mid-header">
						<td></td>
						<th scope="col">Ebooks</th>
						<th scope="col">Updated</th>
						<th scope="col" colspan="10">Download</th>
					</tr>
				</thead>
				<tbody>
					<? foreach($subjects as $subject => $items){ ?>
					<tr>
						<td class="row-header"><?= Formatter::ToPlainText($subject) ?></td>
						<td class="number"><?= Formatter::ToPlainText(number_format($items[0]->Count)) ?></td>
						<td class="number"><?= Formatter::ToPlainText($items[0]->UpdatedString) ?></td>

						<? foreach($items as $item){ ?>
							<td class="download"><a href="<?= $item->Url ?>" download=""><?= $item->Type ?></a></td>
							<td>(<?= Formatter::ToPlainText($item->Size) ?>)</td>
						<? } ?>
					</tr>
					<? } ?>
				</tbody>
			</table>
		</section>

		<section id="downloads-by-year">
			<h2>Downloads by year</h2>
			<table class="download-list">
				<tbody>
			<? foreach($years as $year => $months){
				$yearHeader = Formatter::ToPlainText((string)$year);
			 ?>
				<tr class="year-header">
					<th colspan="13" scope="colgroup" id="<?= $yearHeader ?>"><?= Formatter::ToPlainText((string)$year) ?></th>
				</tr>
				<tr class="mid-header">
					<td></td>
					<th id="<?= $yearHeader ?>-ebooks" scope="col">Ebooks</th>
					<th id="<?= $yearHeader ?>-updated" scope="col">Updated</th>
					<th id="<?= $yearHeader ?>-download" colspan="10" scope="col">Download</th>
				</tr>

				<? foreach($months as $month => $items){
					$monthHeader = $items[0]->Month;
				?>
				<tr>
					<th class="row-header" headers="<?= $yearHeader ?>" id="<?= $monthHeader ?>"><?= Formatter::ToPlainText($month) ?></th>
					<td class="number" headers="<?= $yearHeader ?> <?= $monthHeader ?> <?= $yearHeader ?>-ebooks"><?= Formatter::ToPlainText(number_format($items[0]->Count)) ?></td>
					<td class="number" headers="<?= $yearHeader ?> <?= $monthHeader ?> <?= $yearHeader ?>-updated"><?= Formatter::ToPlainText($items[0]->UpdatedString) ?></td>
					<? foreach($items as $item){ ?>
						<td headers="<?= $yearHeader ?> <?= $monthHeader ?> <?= $yearHeader ?>-download" class="download"><a href="<?= $item->Url ?>" download=""><?= $item->Type ?></a></td>
						<td headers="<?= $yearHeader ?> <?= $monthHeader ?> <?= $yearHeader ?>-download">(<?= Formatter::ToPlainText($item->Size) ?>)</td>
					<? } ?>
				</tr>
				<? } ?>

			<? } ?>
				</tbody>
			</table>
		</section>
	</section>
</main>
<?= Template::Footer() ?>
