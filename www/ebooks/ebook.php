<?
require_once('Core.php');

// See https://developers.google.com/search/docs/data-types/book for RDFa metadata details

use function Safe\preg_match;
use function Safe\preg_replace;
use function Safe\apcu_fetch;
use function Safe\shuffle;

try{
	$urlPath = trim(str_replace('.', '', HttpInput::Str(GET, 'url-path', true, '')), '/'); // Contains the portion of the URL (without query string) that comes after https://standardebooks.org/ebooks/
	$wwwFilesystemPath = EBOOKS_DIST_PATH . $urlPath; // Path to the deployed WWW files for this ebook

	if($urlPath == '' || mb_stripos($wwwFilesystemPath, EBOOKS_DIST_PATH) !== 0){
		// Ensure the path exists and that the root is in our www directory
		throw new Exceptions\InvalidEbookException();
	}

	// Were we passed the author and a work but not the translator?
	// For example:
	// https://standardebooks.org/ebooks/omar-khayyam/the-rubaiyat-of-omar-khayyam
	// Instead of:
	// https://standardebooks.org/ebooks/omar-khayyam/the-rubaiyat-of-omar-khayyam/edward-fitzgerald/edmund-dulac
	// We can tell because if so, the dir we are passed will exist, but there will be no 'src' folder.
	if(is_dir($wwwFilesystemPath) && !is_dir($wwwFilesystemPath . '/src')){
		foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($wwwFilesystemPath)) as $file){
			// This iterator will do a deep scan on the directory. When we hit another directory, the filename will be "." and the path will contain the directory path.
			// We want to find where the "src" directory is, and the directory directly below that will be the final web URL we're looking for.
			if($file->getFilename() == '.' && preg_match('|/src$|ius', $file->getPath())){
				throw new Exceptions\SeeOtherEbookException(preg_replace(['|' . WEB_ROOT . '|ius', '|/src$|ius'], '', $file->getPath()));
			}
		}
	}

	// Do we have the ebook cached?
	try{
		$ebook = apcu_fetch('ebook-' . $wwwFilesystemPath);
	}
	catch(Safe\Exceptions\ApcuException $ex){
		$ebook = new Ebook($wwwFilesystemPath);
	}

	// Generate the bottom carousel.
	$carousel = [];
	$ebooks = Library::GetEbooks();
	shuffle($ebooks);

	$targetCarouselSize = 5;
	if(sizeof($ebooks) < $targetCarouselSize){
		$targetCarouselSize = sizeof($ebooks) - 1;
	}

	$i = 0;
	while(sizeof($carousel) < $targetCarouselSize){
		if(isset($ebooks[$i]) && $ebooks[$i]->Url !== $ebook->Url){
			$carousel[] = $ebooks[$i];
		}

		$i++;
	}
}
catch(Exceptions\SeeOtherEbookException $ex){
	http_response_code(301);
	header('Location: ' . $ex->Url);
	exit();
}
catch(Exceptions\InvalidEbookException $ex){
	http_response_code(404);
	include(WEB_ROOT . '/404.php');
	exit();
}
?><?= Template::Header(['title' => strip_tags($ebook->TitleWithCreditsHtml) . ' - Free ebook download', 'ogType' => 'book', 'coverUrl' => $ebook->DistCoverUrl, 'highlight' => 'ebooks', 'description' => 'Free epub ebook download of the Standard Ebooks edition of ' . $ebook->Title . ': ' . $ebook->Description]) ?>
<main>
	<article class="ebook" typeof="schema:Book" about="<?= $ebook->Url ?>">
		<meta property="schema:description" content="<?= Formatter::ToPlainText($ebook->Description) ?>"/>
		<meta property="schema:url" content="<?= SITE_URL . Formatter::ToPlainText($ebook->Url) ?>"/>
		<? if($ebook->WikipediaUrl){ ?>
		<meta property="schema:sameAs" content="<?= Formatter::ToPlainText($ebook->WikipediaUrl) ?>"/>
		<? } ?>
		<header>
			<hgroup>
				<h1 property="schema:name"><?= Formatter::ToPlainText($ebook->Title) ?></h1>
				<? foreach($ebook->Authors as $author){ ?>
					<? /* We include the `resource` attr here because we can have multiple authors, and in that case their href URLs will link to their combined corpus.
						For example, William Wordsworth & Samuel Coleridge will both link to /ebooks/william-wordsworth_samuel-taylor-coleridge
						But, each author is an individual, so we have to differentiate them in RDFa with `resource` */ ?>
					<? if($author->Name != 'Anonymous'){ ?>
					<h2><a property="schema:author" typeof="schema:Person" href="<?= Formatter::ToPlainText($ebook->AuthorsUrl) ?>" resource="<?= '/ebooks/' . $author->UrlName ?>">
						<span property="schema:name"><?= Formatter::ToPlainText($author->Name) ?></span>
						<meta property="schema:url" content="<?= SITE_URL . Formatter::ToPlainText($ebook->AuthorsUrl) ?>"/>
						<? if($author->NacoafUrl){ ?><meta property="schema:sameAs" content="<?= Formatter::ToPlainText($author->NacoafUrl) ?>"/><? } ?>
						<? if($author->WikipediaUrl){ ?><meta property="schema:sameAs" content="<?= Formatter::ToPlainText($author->WikipediaUrl) ?>"/><? } ?>
						</a>
					</h2>
					<? } ?>
				<? } ?>
			</hgroup>
			<picture>
				<? if($ebook->HeroImage2xAvifUrl !== null){ ?><source srcset="<?= $ebook->HeroImage2xAvifUrl ?> 2x, <?= $ebook->HeroImageAvifUrl ?> 1x" type="image/avif"/><? } ?>
				<source srcset="<?= $ebook->HeroImage2xUrl ?> 2x, <?= $ebook->HeroImageUrl ?> 1x" type="image/jpg"/>
				<img src="<?= $ebook->HeroImage2xUrl ?>" alt="" height="439" width="1318" />
			</picture>
		</header>


		<aside id="reading-ease">
			<p><?= number_format($ebook->WordCount) ?> words (<?= $ebook->ReadingTime ?>) with a reading ease of <?= $ebook->ReadingEase ?> (<?= $ebook->ReadingEaseDescription ?>)</p>
			<? if($ebook->ContributorsHtml !== null){ ?>
			<p><?= $ebook->ContributorsHtml ?></p>
			<? } ?>
			<? if(sizeof($ebook->Collections) > 0){ ?>
				<? foreach($ebook->Collections as $collection){ ?>
					<p><? if($collection->SequenceNumber !== null){ ?>№ <?= number_format($collection->SequenceNumber) ?> in the<? }else{ ?>Part of the<? } ?> <a href="<?= $collection->Url ?>" property="schema:isPartOf"><?= Formatter::ToPlainText(preg_replace('/^The /ius', '', (string)$collection->Name) ?? '') ?></a>
					<? if($collection->Type !== null){ ?>
						<? if(substr_compare(mb_strtolower($collection->Name), mb_strtolower($collection->Type), -strlen(mb_strtolower($collection->Type))) !== 0){ ?>
							<?= $collection->Type ?>.
						<? } ?>
					<? }else{ ?>
							collection.
					<? } ?>
					</p>
				<? } ?>
			<? } ?>
			<ul class="tags"><? foreach($ebook->Tags as $tag){ ?><li><a href="<?= $tag->Url ?>"><?= Formatter::ToPlainText($tag->Name) ?></a></li><? } ?></ul>
		</aside>

		<section id="description">
			<h2>Description</h2>
			<? if(DONATION_DRIVE_ON){ ?>
			<?= Template::DonationProgress() ?>
			<? }elseif(DONATION_ALERT_ON){ ?>
			<?= Template::DonationAlert() ?>
			<? } ?>
			<? if($ebook->LongDescription === null){ ?>
				<p><i>There’s no description for this ebook yet.</i></p>
			<? }else{ ?>
				<?= $ebook->LongDescription ?>
			<? } ?>
		</section>

		<? if($ebook->HasDownloads){ ?>
		<section id="read-free" property="schema:workExample" typeof="schema:Book" resource="<?= Formatter::ToPlainText($ebook->Url) ?>/downloads">
			<meta property="schema:bookFormat" content="http://schema.org/EBook"/>
			<meta property="schema:url" content="<?= Formatter::ToPlainText(SITE_URL . $ebook->Url) ?>"/>
			<meta property="schema:license" content="https://creativecommons.org/publicdomain/zero/1.0/"/>
			<div property="schema:publisher" typeof="schema:Organization">
				<meta property="schema:name" content="Standard Ebooks"/>
				<meta property="schema:logo" content="https://standardebooks.org/images/logo-full.svg"/>
				<meta property="schema:url" content="https://standardebooks.org"/>
			</div>
			<meta property="schema:image" content="<?= Formatter::ToPlainText(SITE_URL . $ebook->DistCoverUrl) ?>"/>
			<meta property="schema:thumbnailUrl" content="<?= Formatter::ToPlainText(SITE_URL . $ebook->Url . '/downloads/cover-thumbnail.jpg') ?>"/>
			<meta property="schema:inLanguage" content="<?= Formatter::ToPlainText($ebook->Language) ?>"/>
			<meta property="schema:datePublished" content="<?= Formatter::ToPlainText($ebook->Timestamp->format('Y-m-d')) ?>"/>
			<meta property="schema:dateModified" content="<?= Formatter::ToPlainText($ebook->ModifiedTimestamp->format('Y-m-d')) ?>"/>
			<div property="schema:potentialAction" typeof="http://schema.org/ReadAction">
				<meta property="schema:actionStatus" content="http://schema.org/PotentialActionStatus"/>
				<div property="schema:target" typeof="schema:EntryPoint">
					<meta property="schema:urlTemplate" content="<?= Formatter::ToPlainText(SITE_URL . $ebook->Url) ?>"/>
					<meta property="schema:actionPlatform" content="http://schema.org/DesktopWebPlatform"/>
					<meta property="schema:actionPlatform" content="http://schema.org/AndroidPlatform"/>
					<meta property="schema:actionPlatform" content="http://schema.org/IOSPlatform"/>
				</div>
				<div property="schema:expectsAcceptanceOf" typeof="schema:Offer">
					<meta property="schema:category" content="nologinrequired"/>
					<div property="schema:eligibleRegion" typeof="schema:Country">
						<meta property="schema:name" content="US"/>
					</div>
				</div>
			</div>
			<?= $ebook->GenerateContributorsRdfa() ?>
			<h2>Read free</h2>
			<p class="us-pd-warning">This ebook is only thought to be free of copyright restrictions in the United States. It may still be under copyright in other countries. If you’re not located in the United States, you must check your local laws to verify that the contents of this ebook are free of copyright restrictions in the country you’re located in before downloading or using this ebook.</p>

			<div class="downloads-container">
				<figure class="<? if($ebook->WordCount < 100000){ ?>small<? }elseif($ebook->WordCount >= 100000 && $ebook->WordCount < 200000){ ?>medium<? }elseif($ebook->WordCount >= 200000 && $ebook->WordCount <= 300000){ ?>large<? }elseif($ebook->WordCount >= 300000 && $ebook->WordCount < 400000){ ?>xlarge<? }elseif($ebook->WordCount >= 400000){ ?>xxlarge<? } ?>">
					<picture>
						<source srcset="<?= $ebook->CoverImage2xAvifUrl ?> 2x, <?= $ebook->CoverImageAvifUrl ?> 1x" type="image/avif"/>
						<source srcset="<?= $ebook->CoverImage2xUrl ?> 2x, <?= $ebook->CoverImageUrl ?> 1x" type="image/jpg"/>
						<img src="<?= $ebook->CoverImageUrl ?>" alt="" height="363" width="242"/>
					</picture>
				</figure>
				<div>
					<section id="download">
						<h3>Download for ereaders</h3>
						<ul>
							<? /* Leave the @download attribute empty to have the browser use the target filename in the save-as dialog */ ?>
							<? if($ebook->EpubUrl !== null){ ?>
							<li property="schema:encoding" typeof="schema:MediaObject">
								<meta property="schema:description" content="epub"/>
								<meta property="schema:encodingFormat" content="application/epub+zip"/>
								<p>
									<span><a property="schema:contentUrl" href="<?= $ebook->EpubUrl ?>" class="epub" download="">Compatible epub</a></span> <span>—</span> <span>All devices and apps except Kindles and Kobos.</span>
								</p>
							</li>
							<? } ?>

							<? if($ebook->Azw3Url !== null){ ?>
							<li property="schema:encoding" typeof="schema:MediaObject">
								<meta property="schema:encodingFormat" content="application/x-mobipocket-ebook"/>
								<p>
									<span><a property="schema:contentUrl" href="<?= $ebook->Azw3Url ?>" class="amazon" download=""><span property="schema:description">azw3</span></a></span> <span>—</span> <span>Kindle devices and apps.<? if($ebook->KindleCoverUrl !== null){ ?> Also download the <a href="<?= $ebook->KindleCoverUrl ?>">Kindle cover thumbnail</a> to see the cover in your Kindle’s library. You may be interested in our <a href="/help/how-to-use-our-ebooks#kindle-faq">Kindle FAQ</a>.<? }else{ ?> Also see our <a href="/how-to-use-our-ebooks#kindle-faq">Kindle FAQ</a>.<? } ?></span>
								</p>
							</li>
							<? } ?>

							<? if($ebook->KepubUrl !== null){ ?>
							<li property="schema:encoding" typeof="schema:MediaObject">
								<meta property="schema:encodingFormat" content="application/kepub+zip"/>
								<p>
									<span><a property="schema:contentUrl" href="<?= $ebook->KepubUrl ?>" class="kobo" download=""><span property="schema:description">kepub</span></a></span> <span>—</span> <span>Kobo devices and apps. You may also be interested in our <a href="/help/how-to-use-our-ebooks#kobo-faq">Kobo FAQ</a>.</span>
								</p>
							</li>
							<? } ?>

							<? if($ebook->AdvancedEpubUrl !== null){ ?>
							<li property="schema:encoding" typeof="schema:MediaObject">
								<meta property="schema:encodingFormat" content="application/epub+zip"/>
								<p>
									<span><a property="schema:contentUrl" href="<?= $ebook->AdvancedEpubUrl ?>" class="epub" download=""><span property="schema:description">Advanced epub</span></a></span> <span>—</span> <span>An advanced format that uses the latest technology not yet fully supported by most ereaders.</span>
								</p>
							</li>
							<? } ?>
						</ul>
						<aside>
							<p>Read about <a href="/help/how-to-use-our-ebooks#which-file-to-download">which file to download</a> and <a href="/help/how-to-use-our-ebooks#transferring-to-your-ereader">how to transfer them to your ereader</a>.</p>
						</aside>
					</section>
					<? if($ebook->TextUrl !== null || $ebook->TextSinglePageUrl !== null){ ?>
					<section id="read-online">
						<h3>Read online</h3>
						<ul>
							<? if($ebook->TextUrl !== null){ ?>
							<li>
								<p>
									<a href="<?= $ebook->TextUrl ?>" class="list">Start from the table of contents</a>
								</p>
							</li>
							<? } ?>
							<? if($ebook->TextSinglePageUrl !== null){ ?>
							<li property="schema:encoding" typeof="schema:mediaObject">
								<meta property="schema:description" content="XHTML"/>
								<meta property="schema:encodingFormat" content="application/xhtml+xml"/>
								<p>
									<a property="schema:contentUrl" href="<?= $ebook->TextSinglePageUrl ?>" class="page">Read on one page</a>
								</p>
							</li>
							<? } ?>
						</ul>
					</section>
					<? } ?>
				</div>
			</div>
		</section>
		<? } ?>

		<section id="history">
			<h2>A brief history of this ebook</h2>
			<ol>
				<? foreach($ebook->GitCommits as $commit){ ?>
				<li>
					<time datetime="<?= $commit->Timestamp->format(DateTime::RFC3339) ?>"><?= $commit->Timestamp->format('M j, Y') ?></time>
					<p><a href="<?= Formatter::ToPlainText($ebook->GitHubUrl) ?>/commit/<?= Formatter::ToPlainText($commit->Hash) ?>"><?= Formatter::ToPlainText($commit->Message) ?></a></p>
				</li>
				<? } ?>
			</ol>
			<? if($ebook->GitHubUrl !== null){ ?>
			<aside>
				<p>Read the <a href="<?= Formatter::ToPlainText($ebook->GitHubUrl) ?>/commits/master">full change history</a>.</p>
			</aside>
			<? } ?>
		</section>

		<section id="details">
			<h2>More details</h2>
			<ul>
				<? if($ebook->GitHubUrl !== null){ ?>
				<li>
					<p><a href="<?= Formatter::ToPlainText($ebook->GitHubUrl) ?>" class="github">This ebook’s source code at GitHub</a></p>
					</li>
				<? } ?>
				<? if($ebook->WikipediaUrl !== null){ ?>
				<li>
					<p><a href="<?= Formatter::ToPlainText($ebook->WikipediaUrl) ?>" class="wikipedia">This book at Wikipedia</a></p>
				</li>
				<? } ?>
				<? foreach($ebook->Sources as $source){ ?>
				<li>
					<p>
						<? if($source->Type == SOURCE_PROJECT_GUTENBERG){ ?><a href="<?= Formatter::ToPlainText($source->Url) ?>" class="project-gutenberg">Transcription at Project Gutenberg</a><? } ?>
						<? if($source->Type == SOURCE_PROJECT_GUTENBERG_AUSTRALIA){ ?><a href="<?= Formatter::ToPlainText($source->Url) ?>" class="project-gutenberg">Transcription at Project Gutenberg Australia</a><? } ?>
						<? if($source->Type == SOURCE_PROJECT_GUTENBERG_CANADA){ ?><a href="<?= Formatter::ToPlainText($source->Url) ?>" class="project-gutenberg">Transcription at Project Gutenberg Canada</a><? } ?>
						<? if($source->Type == SOURCE_WIKISOURCE){ ?><a href="<?= Formatter::ToPlainText($source->Url) ?>" class="wikisource">Transcription at Wikisource</a><? } ?>
						<? if($source->Type == SOURCE_INTERNET_ARCHIVE){ ?><a href="<?= Formatter::ToPlainText($source->Url) ?>" class="internet-archive">Page scans at the Internet Archive</a><? } ?>
						<? if($source->Type == SOURCE_HATHI_TRUST){ ?><a href="<?= Formatter::ToPlainText($source->Url) ?>" class="hathitrust">Page scans at HathiTrust</a><? } ?>
						<? if($source->Type == SOURCE_GOOGLE_BOOKS){ ?><a href="<?= Formatter::ToPlainText($source->Url) ?>" class="google">Page scans at Google Books</a><? } ?>
						<? if($source->Type == SOURCE_FADED_PAGE){ ?><a href="<?= Formatter::ToPlainText($source->Url) ?>" class="globe">Transcription at Faded Page</a><? } ?>
						<? if($source->Type == SOURCE_OTHER){ ?><a href="<?= Formatter::ToPlainText($source->Url) ?>" class="globe"><?= Formatter::ToPlainText(preg_replace(['|https?://(en\.)?|', '|/.+$|'], '', (string)$source->Url) ?? '') /* force type to (string) to satisfy PHPStan */ ?></a><? } ?>
					</p>
				</li>
				<? } ?>
			</ul>
		</section>

		<section id="improve">
			<h2>Improve this ebook</h2>
			<p>Anyone can contribute to make a Standard Ebook better for everyone!</p>
			<p>To report typos, typography errors, or other corrections, see <a href="/contribute/report-errors">how to report errors</a>.</p>
			<? if($ebook->GitHubUrl !== null){ ?><p>If you’re comfortable with technology and want to contribute directly, check out <a href="<?= Formatter::ToPlainText($ebook->GitHubUrl) ?>">this ebook’s GitHub repository</a> and our <a href="/contribute">contributors section</a>.</p><? } ?>
			<p>You can also <a href="/donate">donate to Standard Ebooks</a> to help fund continuing improvement of this and other ebooks.</p>
		</section>

		<? if(sizeof($carousel) > 0){ ?>
		<aside id="more-ebooks">
			<h2>More free ebooks</h2>
			<ul>
				<? foreach($carousel as $carouselEbook){ ?>
				<li>
					<a href="<?= $carouselEbook->Url ?>">
						<picture>
							<? if($carouselEbook->CoverImage2xAvifUrl !== null){ ?><source srcset="<?= $carouselEbook->CoverImage2xAvifUrl ?> 2x, <?= $carouselEbook->CoverImageAvifUrl ?> 1x" type="image/avif"/><? } ?>
							<source srcset="<?= $carouselEbook->CoverImage2xUrl ?> 2x, <?= $carouselEbook->CoverImageUrl ?> 1x" type="image/jpg"/>
							<img src="<?= $carouselEbook->CoverImageUrl ?>" alt="The cover for the Standard Ebooks edition of <?= Formatter::ToPlainText(strip_tags($carouselEbook->TitleWithCreditsHtml)) ?>" height="200" width="134" loading="lazy"/>
						</picture>
					</a>
				</li>
				<? } ?>
			</ul>
		</aside>
		<? } ?>
	</article>
</main>
<?= Template::Footer() ?>
