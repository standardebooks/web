<?
require_once('Core.php');

use function Safe\preg_match;
use function Safe\preg_replace;
use function Safe\apcu_fetch;
use function Safe\shuffle;

try{
	$urlPath = trim(str_replace('.', '', HttpInput::GetString('url-path', true, '')), '/'); // Contains the portion of the URL (without query string) that comes after https://standardebooks.org/ebooks/
	$wwwFilesystemPath = EBOOKS_DIST_PATH . $urlPath; // Path to the deployed WWW files for this ebook

	if($urlPath == '' || mb_stripos($wwwFilesystemPath, EBOOKS_DIST_PATH) !== 0){
		// Ensure the path exists and that the root is in our www directory
		throw new InvalidEbookException();
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
				throw new SeeOtherEbookException(preg_replace(['|' . WEB_ROOT . '|ius', '|/src$|ius'], '', $file->getPath()));
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
catch(SeeOtherEbookException $ex){
	http_response_code(301);
	header('Location: ' . $ex->Url);
	exit();
}
catch(\Exception $ex){
	http_response_code(404);
	include(WEB_ROOT . '/404.php');
	exit();
}
?><?= Template::Header(['title' => strip_tags($ebook->TitleWithCreditsHtml), 'ogType' => 'book', 'coverUrl' => $ebook->DistCoverUrl, 'highlight' => 'ebooks', 'description' => 'The Standard Ebooks edition of ' . $ebook->Title . ': ' . $ebook->Description, 'jsonld' => htmlentities($ebook->GenerateJsonLd(), ENT_NOQUOTES)]) ?>
<main>
	<article class="ebook">
		<header>
			<hgroup>
				<h1><?= Formatter::ToPlainText($ebook->Title) ?></h1>
				<? foreach($ebook->Authors as $author){ ?>
					<? if($author->Name != 'Anonymous'){ ?>
					<h2><a href="<?= Formatter::ToPlainText($ebook->AuthorsUrl) ?>"><?= Formatter::ToPlainText($author->Name) ?></a></h2>
					<? } ?>
				<? } ?>
			</hgroup>
			<picture>
				<? if($ebook->HeroImage2xAvifUrl !== null){ ?><source srcset="<?= $ebook->HeroImage2xAvifUrl ?> 2x, <?= $ebook->HeroImageAvifUrl ?> 1x" type="image/avif"/><? } ?>
				<source srcset="<?= $ebook->HeroImage2xUrl ?> 2x, <?= $ebook->HeroImageUrl ?> 1x" type="image/jpg"/>
				<img src="<?= $ebook->HeroImage2xUrl ?>" alt="The cover for the Standard Ebooks edition of <?= Formatter::ToPlainText(strip_tags($ebook->TitleWithCreditsHtml)) ?>"/>
			</picture>
		</header>

		<aside id="reading-ease">
			<p><?= number_format($ebook->WordCount) ?> words (<?= $ebook->ReadingTime ?>) with a reading ease of <?= $ebook->ReadingEase ?> (<?= $ebook->ReadingEaseDescription ?>)</p>
			<? if($ebook->ContributorsHtml !== null){ ?>
			<p><?= $ebook->ContributorsHtml ?></p>
			<? } ?>
			<? if(sizeof($ebook->Collections) > 0){ ?>
				<? foreach($ebook->Collections as $collection){ ?>
					<p><? if($collection->SequenceNumber !== null){ ?>№ <?= number_format($collection->SequenceNumber) ?> in the<? }else{ ?>Part of the<? } ?> <a href="<?= $collection->Url ?>"><?= Formatter::ToPlainText(preg_replace('/^The /ius', '', (string)$collection->Name) ?? '') ?></a>
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
			<? if($ebook->LongDescription === null){ ?>
				<p><i>There’s no description for this ebook yet.</i></p>
			<? }else{ ?>
				<?= $ebook->LongDescription ?>
			<? } ?>
		</section>

		<? if($ebook->HasDownloads){ ?>
		<section id="read-free">
			<h2>Read free</h2>
			<p class="us-pd-warning">This ebook is only thought to be free of copyright restrictions in the United States. It may still be under copyright in other countries. If you’re not located in the United States, you must check your local laws to verify that the contents of this ebook are free of copyright restrictions in the country you’re located in before downloading or using this ebook.</p>
			<section id="download">
				<h3>Download for ereaders</h3>
				<ul>
					<? if($ebook->EpubUrl !== null){ ?>
					<li><p><span><a href="<?= $ebook->EpubUrl ?>" class="epub">Compatible epub</a> </span><span>—</span> <span>All devices and apps except Kindles and Kobos.</span></p>
					</li>
					<? } ?>

					<? if($ebook->Azw3Url !== null){ ?>
					<li><p><span><a href="<?= $ebook->Azw3Url ?>" class="amazon">azw3</a></span> <span>—</span> <span>Kindle devices and apps.<? if($ebook->KindleCoverUrl !== null){ ?> Also download the <a href="<?= $ebook->KindleCoverUrl ?>">Kindle cover thumbnail</a> to see the cover in your Kindle’s library.<? } ?></span></p>
					</li>
					<? } ?>

					<? if($ebook->KepubUrl !== null){ ?>
					<li><p><span><a href="<?= $ebook->KepubUrl ?>" class="kobo">kepub</a> </span><span>—</span> <span>Kobo devices and apps.</span></p>
					</li>
					<? } ?>

					<? if($ebook->AdvancedEpubUrl !== null){ ?>
					<li><p><span><a href="<?= $ebook->AdvancedEpubUrl ?>" class="epub">Advanced epub</a></span> <span>—</span> <span>An advanced format not yet fully compatible with most ereaders.</span></p>
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
					<li><p><a href="<?= $ebook->TextUrl ?>" class="list">Start from the table of contents</a></p></li>
					<? } ?>
					<? if($ebook->TextSinglePageUrl !== null){ ?>
					<li><p><a href="<?= $ebook->TextSinglePageUrl ?>" class="page">Read on one page</a></p></li>
					<? } ?>
				</ul>
			</section>
			<? } ?>
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
						<? if($source->Type == SOURCE_DP_OLS){ ?><a href="<?= Formatter::ToPlainText($source->Url) ?>" class="distributed-proofreaders">Page scans at Distributed Proofreaders Open Library System</a><? } ?>
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
			<p>You can also <a href="/donate">donate to the Standard Ebooks project</a> to help fund continuing improvement of this and other ebooks.</p>
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
							<img src="<?= $carouselEbook->HeroImage2xUrl ?>" alt="The cover for the Standard Ebooks edition of <?= Formatter::ToPlainText(strip_tags($carouselEbook->TitleWithCreditsHtml)) ?>"/>
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
