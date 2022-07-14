<?
require_once('Core.php');

use function Safe\apcu_fetch;
use function Safe\glob;
use function Safe\preg_replace;
use function Safe\usort;

$name = HttpInput::Str(GET, 'name', false) ?? '';
$type = HttpInput::Str(GET, 'type', false) ?? '';

if($name != 'authors' && $name != 'collections' && $name != 'subjects'){
	Template::Emit404();
}

if($type != 'rss' && $type != 'atom'){
	Template::Emit404();
}

$feeds = [];

$lcTitle = preg_replace('/s$/', '', $name);
$ucTitle = ucfirst($lcTitle);
$ucType = 'RSS 2.0';
if($type === 'atom'){
	$ucType = 'Atom 1.0';
}

try{
	$feeds = apcu_fetch('feeds-index-' . $type . '-' . $name);
}
catch(Safe\Exceptions\ApcuException $ex){
	$files = glob(WEB_ROOT . '/feeds/' . $type . '/' . $name . '/*.xml');

	$feeds = [];

	foreach($files as $file){
		$obj = new stdClass();
		$obj->Url = '/feeds/' . $type . '/' . $name . '/' . basename($file, '.xml');

		$obj->Label  = exec('attr -g se-label ' . escapeshellarg($file)) ?: null;
		if($obj->Label == null){
			$obj->Label = basename($file, '.xml');
		}

		$obj->LabelSort  = exec('attr -g se-label-sort ' . escapeshellarg($file)) ?: null;
		if($obj->LabelSort == null){
			$obj->LabelSort = basename($file, '.xml');
		}

		$feeds[] = $obj;
	}

	$collator = Collator::create('en_US'); // Used for sorting letters with diacritics like in author names
	if($collator !== null){
		usort($feeds, function($a, $b) use($collator){ return $collator->compare($a->LabelSort, $b->LabelSort); });
	}

	apcu_store('feeds-index-' . $type . '-' . $name, $feeds, 43200); // 12 hours
}
?><?= Template::Header(['title' => $ucType . ' Ebook Feeds by ' . $ucTitle, 'description' => 'A list of available ' . $ucType . ' feeds of Standard Ebooks ebooks by ' . $lcTitle . '.']) ?>
<main>
	<article>
		<h1><?= $ucType ?> Ebook Feeds by <?= $ucTitle ?></h1>
		<?= Template::FeedHowTo() ?>
		<section id="ebooks-by-<?= $lcTitle ?>">
			<h2>Ebooks by <?= $lcTitle ?></h2>
			<ul class="feed">
				<? foreach($feeds as $feed){ ?>
				<li>
					<p><a href="<?= Formatter::ToPlainText($feed->Url) ?>"><?= Formatter::ToPlainText($feed->Label) ?></a></p>
					<p class="url"><? if($GLOBALS['User'] !== null){ ?>https://<?= rawurlencode($GLOBALS['User']->Email) ?>@<?= SITE_DOMAIN ?><? }else{ ?><?= SITE_URL ?><? } ?><?= Formatter::ToPlainText($feed->Url) ?></p>
				</li>
				<? } ?>
			</ul>
		</section>
	</article>
</main>
<?= Template::Footer() ?>
