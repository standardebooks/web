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
			if($file->getFilename() == '.' && preg_match('/\/src$/ius', $file->getPath())){
				throw new SeeOtherEbookException(preg_replace(['|' . SITE_ROOT . '/www|ius', '|/src$|ius'], '', $file->getPath()));
			}
		}
	}

	// Do we have the ebook cached?
	try{
		$ebook = apcu_fetch('ebook-' . $wwwFilesystemPath);
	}
	catch(Safe\Exceptions\ApcuException $ex){
		$ebook = new Ebook($wwwFilesystemPath);
		apcu_store('ebook-' . $wwwFilesystemPath, $ebook);
	}

	// Generate the bottom carousel.
	$carousel = [];
	$ebooks = Library::GetEbooks();
	shuffle($ebooks);

	for($i = 0; $i < 5; $i++){
		if(isset($ebooks[$i])){
			$carousel[] = $ebooks[$i];
		}
	}
}
catch(SeeOtherEbookException $ex){
	http_response_code(301);
	header('Location: ' . $ex->Url);
	exit();
}
catch(\Exception $ex){
	http_response_code(404);
	include(SITE_ROOT . '/web/www/404.php');
	exit();
}
?><?= Template::Header(['title' => strip_tags($ebook->TitleWithCreditsHtml), 'ogType' => 'book', 'coverUrl' => $ebook->DistCoverUrl, 'highlight' => 'ebooks', 'description' => 'The Standard Ebooks edition of ' . $ebook->Title . ': ' . $ebook->Description, 'jsonld' => $ebook->GenerateJsonLd()]) ?>
<main>
	<article class="ebook">
		<header>
			<div>
				<h1><?= Formatter::ToPlainText($ebook->Title) ?></h1>
				<? foreach($ebook->Authors as $author){ ?>
				<p><a href="<?= Formatter::ToPlainText($ebook->AuthorsUrl) ?>"><?= Formatter::ToPlainText($author->Name) ?></a></p>
				<? } ?>
			</div>
			<img src="<?= $ebook->HeroImage2xUrl ?>" alt="The cover for the Standard Ebooks edition of <?= $ebook->Title ?>." />
		</header>

		<aside id="reading-ease">
			<p><?= number_format($ebook->WordCount) ?> words (<?= $ebook->ReadingTime ?>) with a reading ease of <?= $ebook->ReadingEase ?> (<?= $ebook->ReadingEaseDescription ?>)</p>
			<? if($ebook->ContributorsHtml !== null){ ?>
			<p><?= $ebook->ContributorsHtml ?></p>
			<? } ?>
			<? if(sizeof($ebook->Collections) > 0){ ?>
				<p>Part of the
					<? for($i = 0; $i < sizeof($ebook->Collections); $i++){ ?>
					<a href="<?= $ebook->Collections[$i]->Url ?>"><?= Formatter::ToPlainText(preg_replace('/^The /ius', '', (string)$ebook->Collections[$i]->Name) ?? '') ?></a><? if(sizeof($ebook->Collections) > 2){ ?><? if($i == sizeof($ebook->Collections) - 2){ ?>, and <? }elseif($i != sizeof($ebook->Collections) - 1){ ?>, <? } ?><? }elseif($i == sizeof($ebook->Collections) - 2){ ?> and <? } ?>
					<? } ?>
				collection<? if(sizeof($ebook->Collections) > 1){ ?>s<? } ?>.</p>
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
		<section id="download">
			<h2>Free download</h2>
			<div class="us-pd-warning">
				<p>This ebook is only thought to be free of copyright restrictions in the United States. It may still be under copyright in other countries. If you’re not located in the United States, you must check your local laws to verify that the contents of this ebook are free of copyright restrictions in the country you’re located in before downloading or using this ebook.</p>
			</div>
			<ul>
				<? if($ebook->EpubUrl !== null){ ?>
				<li><p><span><a href="<?= $ebook->EpubUrl ?>" class="epub">epub</a> </span><span>—</span> <span>All devices and apps except Amazon Kindle and Kobo.</span></p>
				</li>
				<? } ?>

				<? if($ebook->Azw3Url !== null){ ?>
				<li><p><span><a href="<?= $ebook->Azw3Url ?>" class="amazon">azw3</a></span> <span>—</span> <span>Amazon Kindle devices and apps.<? if($ebook->KindleCoverUrl !== null){ ?> Also download the <a href="<?= $ebook->KindleCoverUrl ?>">Kindle cover thumbnail</a> to see the cover in your Kindle’s library.<? } ?></span></p>
				</li>
				<? } ?>

				<? if($ebook->KepubUrl !== null){ ?>
				<li><p><span><a href="<?= $ebook->KepubUrl ?>" class="kobo">kepub</a> </span><span>—</span> <span>Kobo devices and apps.</span></p>
				</li>
				<? } ?>

				<? if($ebook->Epub3Url !== null){ ?>
				<li><p><span><a href="<?= $ebook->Epub3Url ?>" class="epub">epub3</a></span> <span>—</span> <span>Advanced format not yet fully compatible with most ereaders.</span></p>
				</li>
				<? } ?>
			</ul>
			<aside>
				<p>Read about <a href="/help/how-to-use-our-ebooks#which-file-to-download">which file to download</a> and <a href="/help/how-to-use-our-ebooks#transferring-to-your-ereader">how to transfer them to your ereader</a>.</p>
			</aside>
		</section>
		<? } ?>

		<section id="history">
			<h2>A brief history of this ebook</h2>
			<ol>
				<? foreach($ebook->GitCommits as $commit){ ?>
				<li>
					<time datetime="<?= $commit->Timestamp->format(DateTime::RFC3339) ?>"><?= $commit->Timestamp->format('M j, Y') ?></time>
					<p><?= Formatter::ToPlainText($commit->Message) ?></p>
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
			<? if($ebook->GitHubUrl !== null){ ?><p>If you’re comfortable with technology and want to contribute directly, check out <a href="<?= Formatter::ToPlainText($ebook->GitHubUrl) ?>">this ebook’s GitHub repository</a> and our <a href="/contribute/">contributors section</a>.</p><? } ?>
		</section>

		<aside id="more-ebooks">
			<h2>More free ebooks</h2>
			<ul>
				<? foreach($carousel as $carouselEbook){ ?>
				<li><a href="<?= $carouselEbook->Url ?>"><img src="<?= $carouselEbook->CoverImage2xUrl ?>"<? /* srcset="<?= $carouselEbook->CoverImage2xUrl ?> 2x, <?= $carouselEbook->CoverImageUrl ?> 1x" */ ?> alt="The cover for the Standard Ebooks edition of <?= Formatter::ToPlainText($carouselEbook->Title) ?>" title="<?= Formatter::ToPlainText($carouselEbook->Title) ?>" /></a></li>
				<? } ?>
			</ul>
		</aside>
	</article>
</main>
<?= Template::Footer() ?>
