<?
// See <https://developers.google.com/search/docs/data-types/book> for RDFa metadata details.

use Safe\DateTimeImmutable;
use function Safe\preg_match;
use function Safe\preg_replace;
use function Safe\shuffle;

$ebook = null;
$transcriptionSources = [];
$scanSources = [];
$otherSources = [];
$carousel = [];
$carouselTag = null;
$targetCarouselSize = 5;

try{
	$identifier = EBOOKS_IDENTIFIER_PREFIX . trim(str_replace('.', '', HttpInput::Str(GET, 'url-path') ?? ''), '/'); // Contains the portion of the URL (without query string) that comes after `https://standardebooks.org/ebooks/`.

	$ebook = Ebook::GetByIdentifier($identifier);

	if($ebook->IsPlaceholder()){
		require(WEB_ROOT . '/ebook-placeholders/get.php');
		exit();
	}

	// Divide our sources into transcriptions and scans.
	foreach($ebook->Sources as $source){
		switch($source->Type){
			case Enums\EbookSourceType::ProjectGutenberg:
			case Enums\EbookSourceType::ProjectGutenbergAustralia:
			case Enums\EbookSourceType::ProjectGutenbergCanada:
			case Enums\EbookSourceType::Wikisource:
			case Enums\EbookSourceType::FadedPage:
				$transcriptionSources[] = $source;
				break;

			case Enums\EbookSourceType::InternetArchive:
			case Enums\EbookSourceType::HathiTrust:
			case Enums\EbookSourceType::GoogleBooks:
				$scanSources[] = $source;
				break;

			case Enums\EbookSourceType::Other:
				$otherSources[] = $source;
				break;
		}
	}

	if(sizeof($ebook->Tags) > 0){
		$carouselTag = $ebook->Tags[rand(0, sizeof($ebook->Tags) - 1)];
	}

	$carousel = Ebook::GetAllByRelated($ebook, $targetCarouselSize, $carouselTag);
}
catch(Exceptions\EbookNotFoundException){
	// Were we passed the author and a work but not the translator?
	// For example: <https://standardebooks.org/ebooks/omar-khayyam/the-rubaiyat-of-omar-khayyam>
	// Instead of: <https://standardebooks.org/ebooks/omar-khayyam/the-rubaiyat-of-omar-khayyam/edward-fitzgerald>.
	try{
		$ebook = Ebook::GetByIdentifierStartingWith($identifier);

		// Found, redirect.
		http_response_code(Enums\HttpCode::MovedPermanently->value);
		header('Location: ' . $ebook->Url);
		exit();
	}
	catch(Exceptions\EbookNotFoundException){
		// Still not found, continue.
	}

	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
?><?= Template::Header(title: strip_tags($ebook->TitleWithCreditsHtml) . ' - Free ebook download', ogType: 'book', coverUrl: $ebook->DistCoverUrl, highlight: 'ebooks', description: 'Free epub ebook download of the Standard Ebooks edition of ' . $ebook->Title . ': ' . $ebook->Description, canonicalUrl: SITE_URL . $ebook->Url) ?>
<main>
	<article class="ebook" typeof="schema:Book" about="<?= $ebook->Url ?>">
		<meta property="schema:description" content="<?= Formatter::EscapeHtml($ebook->Description) ?>"/>
		<meta property="schema:url" content="<?= SITE_URL . Formatter::EscapeHtml($ebook->Url) ?>"/>
		<? if($ebook->WikipediaUrl){ ?>
			<meta property="schema:sameAs" content="<?= Formatter::EscapeHtml($ebook->WikipediaUrl) ?>"/>
		<? } ?>
		<header>
			<hgroup>
				<h1 property="schema:name"><?= Formatter::EscapeHtml($ebook->Title) ?></h1>
				<? foreach($ebook->Authors as $author){ ?>
					<?
						/* We include the `resource` attr here because we can have multiple authors, and in that case their href URLs will link to their combined corpus.

						For example, William Wordsworth & Samuel Coleridge will both link to `/ebooks/william-wordsworth_samuel-taylor-coleridge`.

						But, each author is an individual, so we have to differentiate them in RDFa with `resource`.
					*/ ?>
					<? if($author->Name != 'Anonymous'){ ?>
						<p>
							<a property="schema:author" typeof="schema:Person" href="<?= Formatter::EscapeHtml($ebook->AuthorsUrl) ?>" resource="<?= '/ebooks/' . $author->UrlName ?>">
								<span property="schema:name"><?= Formatter::EscapeHtml($author->Name) ?></span>
								<meta property="schema:url" content="<?= SITE_URL . Formatter::EscapeHtml($ebook->AuthorsUrl) ?>"/>
								<? if($author->NacoafUrl){ ?>
									<meta property="schema:sameAs" content="<?= Formatter::EscapeHtml($author->NacoafUrl) ?>"/>
								<? } ?>
								<? if($author->WikipediaUrl){ ?>
									<meta property="schema:sameAs" content="<?= Formatter::EscapeHtml($author->WikipediaUrl) ?>"/>
								<? } ?>
							</a>
						</p>
					<? } ?>
				<? } ?>
			</hgroup>
			<picture>
				<? if($ebook->HeroImage2xAvifUrl !== null){ ?>
					<source srcset="<?= $ebook->HeroImage2xAvifUrl ?> 2x, <?= $ebook->HeroImageAvifUrl ?> 1x" type="image/avif"/>
				<? } ?>
				<source srcset="<?= $ebook->HeroImage2xUrl ?> 2x, <?= $ebook->HeroImageUrl ?> 1x" type="image/jpg"/>
				<img src="<?= $ebook->HeroImage2xUrl ?>" alt="" height="439" width="1318" />
			</picture>
		</header>


		<aside id="reading-ease">
			<? if($ebook->WordCount !== null){ ?>
				<meta property="schema:wordCount" content="<?= Formatter::EscapeHtml((string)$ebook->WordCount) ?>"/>
				<p><?= number_format($ebook->WordCount) ?> words (<?= $ebook->ReadingTime ?>) with a reading ease of <?= $ebook->ReadingEase ?> (<?= $ebook->ReadingEaseDescription ?>)</p>
			<? } ?>
			<? if($ebook->ContributorsHtml != ''){ ?>
				<p><?= $ebook->ContributorsHtml ?></p>
			<? } ?>
			<? if(sizeof($ebook->CollectionMemberships) > 0){ ?>
				<? foreach($ebook->CollectionMemberships as $collectionMembership){ ?>
					<p>
						<?= Template::CollectionDescriptor(collectionMembership: $collectionMembership) ?>.
					</p>
				<? } ?>
			<? } ?>
			<ul class="tags">
				<? foreach($ebook->Tags as $tag){ ?>
					<li>
						<a href="<?= $tag->Url ?>"><?= Formatter::EscapeHtml($tag->Name) ?></a>
					</li>
				<? } ?>
			</ul>
		</aside>

		<section id="description">
			<h2>Description</h2>
			<?= Template::DonationCounter() ?>
			<?= Template::DonationProgress() ?>

			<?= Template::DonationAlert() ?>

			<?= $ebook->LongDescription ?>
		</section>

		<? if($ebook->HasDownloads){ ?>
			<section id="read-free" property="schema:workExample" typeof="schema:Book" resource="<?= Formatter::EscapeHtml($ebook->Url) ?>/downloads">
				<meta property="schema:bookFormat" content="http://schema.org/EBook"/>
				<meta property="schema:url" content="<?= Formatter::EscapeHtml(SITE_URL . $ebook->Url) ?>"/>
				<meta property="schema:license" content="https://creativecommons.org/publicdomain/zero/1.0/"/>
				<div property="schema:publisher" typeof="schema:Organization">
					<meta property="schema:name" content="Standard Ebooks"/>
					<meta property="schema:logo" content="https://standardebooks.org/images/logo-full.svg"/>
					<meta property="schema:url" content="https://standardebooks.org"/>
				</div>
				<meta property="schema:image" content="<?= Formatter::EscapeHtml(SITE_URL . $ebook->DistCoverUrl) ?>"/>
				<meta property="schema:thumbnailUrl" content="<?= Formatter::EscapeHtml(SITE_URL . $ebook->Url . '/downloads/cover-thumbnail.jpg') ?>"/>
				<meta property="schema:inLanguage" content="<?= Formatter::EscapeHtml($ebook->Language) ?>"/>
				<? if($ebook->EbookCreated !== null){ ?>
					<meta property="schema:datePublished" content="<?= Formatter::EscapeHtml($ebook->EbookCreated->format('Y-m-d')) ?>"/>
				<? } ?>
				<? if($ebook->EbookUpdated !== null){ ?>
					<meta property="schema:dateModified" content="<?= Formatter::EscapeHtml($ebook->EbookUpdated->format('Y-m-d')) ?>"/>
				<? } ?>
				<div property="schema:potentialAction" typeof="http://schema.org/ReadAction">
					<meta property="schema:actionStatus" content="http://schema.org/PotentialActionStatus"/>
					<div property="schema:target" typeof="schema:EntryPoint">
						<meta property="schema:urlTemplate" content="<?= Formatter::EscapeHtml(SITE_URL . $ebook->Url) ?>"/>
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
				<p class="us-pd-warning">This ebook is thought to be free of copyright restrictions in the United States. It may still be under copyright in other countries. If you’re not located in the United States, you must check your local laws to verify that this ebook is free of copyright restrictions in the country you’re located in before accessing, downloading, or using it.</p>

				<div class="downloads-container">
					<?= Template::RealisticEbook(ebook: $ebook) ?>
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
											<span><a property="schema:contentUrl" href="<?= $ebook->GetDownloadUrl(Enums\EbookFormatType::Epub) ?>" class="epub">Compatible epub</a></span> <span>—</span> <span>All devices and apps except Kindles and Kobos.</span>
										</p>
									</li>
								<? } ?>

								<? if($ebook->Azw3Url !== null){ ?>
									<li property="schema:encoding" typeof="schema:MediaObject">
										<meta property="schema:encodingFormat" content="application/x-mobipocket-ebook"/>
										<p>
											<span><a property="schema:contentUrl" href="<?= $ebook->GetDownloadUrl(Enums\EbookFormatType::Azw3) ?>" class="amazon"><span property="schema:description">azw3</span></a></span> <span>—</span> <span>Kindle devices and apps.<? if($ebook->KindleCoverUrl !== null){ ?> Also download the <a href="<?= $ebook->KindleCoverUrl ?>">Kindle cover thumbnail</a> to see the cover in your Kindle’s library. Despite what you’ve been told, <a href="/help/how-to-use-our-ebooks#kindle-epub">Kindle does not natively support epub.</a> You may also be interested in our <a href="/help/how-to-use-our-ebooks#kindle-faq">Kindle FAQ</a>.<? }else{ ?> Also see our <a href="/how-to-use-our-ebooks#kindle-faq">Kindle FAQ</a>.<? } ?></span>
										</p>
									</li>
								<? } ?>

								<? if($ebook->KepubUrl !== null){ ?>
									<li property="schema:encoding" typeof="schema:MediaObject">
										<meta property="schema:encodingFormat" content="application/kepub+zip"/>
										<p>
											<span><a property="schema:contentUrl" href="<?= $ebook->GetDownloadUrl(Enums\EbookFormatType::Kepub) ?>" class="kobo"><span property="schema:description">kepub</span></a></span> <span>—</span> <span>Kobo devices and apps. You may also be interested in our <a href="/help/how-to-use-our-ebooks#kobo-faq">Kobo FAQ</a>.</span>
										</p>
									</li>
								<? } ?>

								<? if($ebook->AdvancedEpubUrl !== null){ ?>
									<li property="schema:encoding" typeof="schema:MediaObject">
										<meta property="schema:encodingFormat" content="application/epub+zip"/>
										<p>
											<span><a property="schema:contentUrl" href="<?= $ebook->GetDownloadUrl(Enums\EbookFormatType::AdvancedEpub) ?>" class="epub"><span property="schema:description">Advanced epub</span></a></span> <span>—</span> <span>An advanced format that uses the latest technology not yet fully supported by most ereaders.</span>
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
											<p<? if($ebook->TextSinglePageByteCount >= EBOOK_SINGLE_PAGE_SIZE_WARNING){ ?> class="has-size"<? } ?>>
												<a property="schema:contentUrl" href="<?= $ebook->TextSinglePageUrl ?>" class="page">Read on one page</a><? if($ebook->TextSinglePageByteCount >= EBOOK_SINGLE_PAGE_SIZE_WARNING){ ?><span><?= $ebook->TextSinglePageSizeFormatted ?></span><? } ?>
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
						<time datetime="<?= $commit->Created->format(DateTimeImmutable::RFC3339) ?>"><?= $commit->Created->format(Enums\DateTimeFormat::ShortDate->value) ?></time>
						<p>
							<a href="<?= Formatter::EscapeHtml($ebook->GitHubUrl) ?>/commit/<?= Formatter::EscapeHtml($commit->Hash) ?>"><?= Formatter::EscapeHtml($commit->Message) ?></a>
						</p>
					</li>
				<? } ?>
			</ol>
			<? if($ebook->GitHubUrl !== null){ ?>
				<aside>
					<p>Read the <a href="<?= Formatter::EscapeHtml($ebook->GitHubUrl) ?>/commits/master">full change history</a>.</p>
				</aside>
			<? } ?>
		</section>

		<? if($ebook->GitHubUrl !== null || $ebook->WikipediaUrl !== null){ ?>
			<section id="details">
				<h2>More details</h2>
				<ul>
					<? if($ebook->GitHubUrl !== null){ ?>
						<li>
							<p>
								<a href="<?= Formatter::EscapeHtml($ebook->GitHubUrl) ?>" class="github">This ebook’s source code at GitHub</a>
							</p>
						</li>
					<? } ?>
					<? if($ebook->WikipediaUrl !== null){ ?>
						<li>
							<p>
								<a href="<?= Formatter::EscapeHtml($ebook->WikipediaUrl) ?>" class="wikipedia">This book at Wikipedia</a>
							</p>
						</li>
					<? } ?>
				</ul>
			</section>
		<? } ?>

		<? if(sizeof($transcriptionSources) > 0 || sizeof($scanSources) > 0 || sizeof($otherSources) > 0){ ?>
			<section id="sources">
				<h2>Sources</h2>
				<? if(sizeof($transcriptionSources) > 0){ ?>
					<section id="transcriptions">
						<h3>Transcriptions</h3>
						<ul>
							<? foreach($transcriptionSources as $source){ ?>
								<li>
									<p>
										<? if($source->Type == Enums\EbookSourceType::ProjectGutenberg){ ?>
											<a href="<?= Formatter::EscapeHtml($source->Url) ?>" class="project-gutenberg">Transcription at Project Gutenberg</a>
										<? }elseif($source->Type == Enums\EbookSourceType::ProjectGutenbergAustralia){ ?>
											<a href="<?= Formatter::EscapeHtml($source->Url) ?>" class="project-gutenberg">Transcription at Project Gutenberg Australia</a>
										<? }elseif($source->Type == Enums\EbookSourceType::ProjectGutenbergCanada){ ?>
											<a href="<?= Formatter::EscapeHtml($source->Url) ?>" class="project-gutenberg">Transcription at Project Gutenberg Canada</a>
										<? }elseif($source->Type == Enums\EbookSourceType::Wikisource){ ?>
											<a href="<?= Formatter::EscapeHtml($source->Url) ?>" class="wikisource">Transcription at Wikisource</a>
										<? }elseif($source->Type == Enums\EbookSourceType::FadedPage){ ?>
											<a href="<?= Formatter::EscapeHtml($source->Url) ?>" class="globe">Transcription at Faded Page</a>
										<? }else{?>
											<a href="<?= Formatter::EscapeHtml($source->Url) ?>" class="globe">Transcription</a>
										<? } ?>
									</p>
								</li>
							<? } ?>
						</ul>
					</section>
				<? } ?>
				<? if(sizeof($scanSources) > 0){ ?>
					<section id="page-scans">
						<h3>Page scans</h3>
						<ul>
							<? foreach($scanSources as $source){ ?>
								<li>
									<p>
										<? if($source->Type == Enums\EbookSourceType::InternetArchive){ ?>
											<a href="<?= Formatter::EscapeHtml($source->Url) ?>" class="internet-archive">Page scans at the Internet Archive</a>
										<? }elseif($source->Type == Enums\EbookSourceType::HathiTrust){ ?>
											<a href="<?= Formatter::EscapeHtml($source->Url) ?>" class="hathitrust">Page scans at HathiTrust</a>
										<? }elseif($source->Type == Enums\EbookSourceType::GoogleBooks){ ?>
											<a href="<?= Formatter::EscapeHtml($source->Url) ?>" class="google">Page scans at Google Books</a>
										<? }else{ ?>
											<a href="<?= Formatter::EscapeHtml($source->Url) ?>" class="globe">Page scans</a>
										<? } ?>
									</p>
								</li>
							<? } ?>
						</ul>
					</section>
				<? } ?>
				<? if(sizeof($otherSources) > 0){ ?>
					<section id="other-sources">
						<h3>Other sources</h3>
						<ul>
							<? foreach($otherSources as $source){ ?>
								<li>
									<p>
										<? if($source->Type == Enums\EbookSourceType::Other){ ?><a href="<?= Formatter::EscapeHtml($source->Url) ?>" class="globe"><?= Formatter::EscapeHtml(preg_replace(['|https?://(en\.)?|', '|/.+$|'], '', (string)$source->Url)) /* force type to (string) to satisfy PHPStan */ ?></a><? } ?>
									</p>
								</li>
							<? } ?>
						</ul>
					</section>
				<? } ?>
			</section>
		<? } ?>

		<section id="improve-this-ebook">
			<h2>Improve this ebook</h2>
			<p>Anyone can contribute to make a Standard Ebook better for everyone!</p>
			<p>To report typos, typography errors, or other corrections, see <a href="/contribute/report-errors">how to report errors</a>.</p>
			<? if($ebook->GitHubUrl !== null){ ?>
				<p>If you’re comfortable with technology and want to contribute directly, check out <a href="<?= Formatter::EscapeHtml($ebook->GitHubUrl) ?>">this ebook’s GitHub repository</a> and our <a href="/contribute">contributors section</a>.</p>
			<? } ?>
			<p>You can also <a href="/donate">donate to Standard Ebooks</a> to help fund continuing improvement of this and other ebooks.</p>
		</section>

		<? if(Session::$User?->Benefits->CanEditEbooks){ ?>
			<?= Template::EbookMetadata(ebook: $ebook) ?>
		<? } ?>

		<? if(sizeof($carousel) > 0){ ?>
			<aside id="more-ebooks">
				<h2>More free<? if($carouselTag !== null){ ?> <?= strtolower($carouselTag->Name) ?><? } ?> ebooks</h2>
				<?= Template::EbookCarousel(carousel: $carousel, isMultiSize: true) ?>
			</aside>
		<? } ?>
	</article>
</main>
<?= Template::Footer() ?>
