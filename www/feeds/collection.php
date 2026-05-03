<?
/**
 * GET		/feeds/:feed-format/:collection-type
 */

use function Safe\apcu_fetch;
use function Safe\preg_replace;

$collectionType = Enums\FeedCollectionType::tryFrom(HttpInput::Str(GET, 'collection-type') ?? '');
$feedFormat = Enums\FeedFormatType::tryFrom(HttpInput::Str(GET, 'feed-format') ?? '');

if(
	$collectionType === null
	||
	$feedFormat === null
	||
	(
		$feedFormat != Enums\FeedFormatType::Rss
		&&
		$feedFormat != Enums\FeedFormatType::Atom
	)
){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}

$feeds = [];

$lcTitle = preg_replace('/s$/', '', $collectionType->value);
$ucTitle = ucfirst($lcTitle);

try{
	/** @var array<stdClass> $feeds */
	$feeds = apcu_fetch('feeds-index-' . $feedFormat->value . '-' . $collectionType->value);
}
catch(Safe\Exceptions\ApcuException){
	$feeds = Feed::RebuildFeedsCache($feedFormat, $collectionType);

	if($feeds === null){
		Template::ExitWithCode(Enums\HttpCode::NotFound);
	}
}
?><?= Template::Header(title: $feedFormat->GetDisplayName() . ' Ebook Feeds by ' . $ucTitle, description: 'A list of available ' . $feedFormat->GetDisplayName() . ' feeds of Standard Ebooks ebooks by ' . $lcTitle . '.') ?>
<main>
	<article>
		<h1><?= $feedFormat->GetDisplayName() ?> Ebook Feeds by <?= $ucTitle ?></h1>
		<?= Template::FeedHowTo() ?>
		<section id="ebooks-by-<?= $lcTitle ?>">
			<h2>Ebooks by <?= $lcTitle ?></h2>
			<ul class="feed">
				<? foreach($feeds as $feed){ ?>
					<li>
						<p>
							<a href="<?= Formatter::EscapeHtml($feed->Url) ?>"><?= Formatter::EscapeHtml($feed->Label) ?></a>
						</p>
						<p class="url">
							<? if(isset(Session::$User->Email)){ ?>https://<?= rawurlencode(Session::$User->Email) ?>@<?= SITE_DOMAIN ?><? }else{ ?><?= SITE_URL ?><? } ?><?= Formatter::EscapeHtml($feed->Url) ?>
						</p>
					</li>
				<? } ?>
			</ul>
		</section>
	</article>
</main>
<?= Template::Footer() ?>
