<?
use function Safe\apcu_fetch;
use function Safe\glob;
use function Safe\preg_replace;
use function Safe\usort;

$class = HttpInput::Str(GET, 'class', false) ?? '';
$type = HttpInput::Str(GET, 'type', false) ?? '';

if($class != 'authors' && $class != 'collections' && $class != 'subjects'){
	Template::Emit404();
}

if($type != 'rss' && $type != 'atom'){
	Template::Emit404();
}

$feeds = [];

$lcTitle = preg_replace('/s$/', '', $class);
$ucTitle = ucfirst($lcTitle);
$ucType = 'RSS 2.0';
if($type === 'atom'){
	$ucType = 'Atom 1.0';
}

try{
	$feeds = apcu_fetch('feeds-index-' . $type . '-' . $class);
}
catch(Safe\Exceptions\ApcuException){
	$feeds = Library::RebuildFeedsCache($type, $class);
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
					<p><a href="<?= Formatter::EscapeHtml($feed->Url) ?>"><?= Formatter::EscapeHtml($feed->Label) ?></a></p>
					<p class="url"><? if($GLOBALS['User'] !== null){ ?>https://<?= rawurlencode($GLOBALS['User']->Email) ?>@<?= SITE_DOMAIN ?><? }else{ ?><?= SITE_URL ?><? } ?><?= Formatter::EscapeHtml($feed->Url) ?></p>
				</li>
				<? } ?>
			</ul>
		</section>
	</article>
</main>
<?= Template::Footer() ?>
