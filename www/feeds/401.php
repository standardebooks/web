<?
use function Safe\preg_match;

$feedType = null;
preg_match('/^\/feeds\/(opds|atom)/ius', Http::$Request->RelativePath, $matches);

if(isset($matches[1])){
	$feedType = Enums\FeedFormatType::tryFrom(strtolower($matches[1]));
}

$title = 'Standard Ebooks Ebook Feeds';
if($feedType == Enums\FeedFormatType::Opds){
	$title = 'The Standard Ebooks OPDS Feed';
}

if($feedType == Enums\FeedFormatType::Atom){
	$title = 'Standard Ebooks RSS/Atom Feeds';
}

?><?= Template::Header(title: 'The Standard Ebooks OPDS feed', description: 'Get access to the Standard Ebooks OPDS feed for use in ereading programs in scripting.') ?>
<main>
	<section class="narrow has-hero">
		<? if($feedType == Enums\FeedFormatType::Opds){ ?>
			<h1>The Standard Ebooks OPDS Feed</h1>
		<? }elseif($feedType == Enums\FeedFormatType::Atom){ ?>
			<h1>Standard Ebooks RSS/Atom Feeds</h1>
		<? }else{ ?>
			<h1>Standard Ebooks Ebook Feeds</h1>
		<? } ?>
		<picture data-caption="Rack Pictures for Dr. Nones. William A. Mitchell, 1879">
			<source srcset="/images/rack-picture-for-dr-nones@2x.avif 2x, /images/rack-picture-for-dr-nones.avif 1x" type="image/avif"/>
			<source srcset="/images/rack-picture-for-dr-nones@2x.jpg 2x, /images/rack-picture-for-dr-nones.jpg 1x" type="image/jpeg"/>
			<img src="/images/rack-picture-for-dr-nones@2x.jpg" alt="Postal mail attached to a billboard."/>
		</picture>
		<ul class="message error">
			<li>
				<p>Make a donation to <a href="/donate#patrons-circle">join the Patrons Circle</a> and get access our ebook feeds.</p>
			</li>
		</ul>
		<?= Template::FeedHowTo() ?>
	</section>
</main>
<?= Template::Footer() ?>
