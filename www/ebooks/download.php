<?
use Safe\DateTimeImmutable;

// If the user is not logged in, or has less than some amount of downloads, show a thank-you page

$ebook = null;
$downloadCount = $_COOKIE['download-count'] ?? 0;
$showThankYouPage = $GLOBALS['User'] === null && $downloadCount < 5;
$downloadUrl = null;

try{
	$urlPath = HttpInput::Str(GET, 'url-path') ?? null;
	$identifier = EBOOKS_IDENTIFIER_PREFIX . $urlPath;
	$ebook = Ebook::GetByIdentifier($identifier);

	$format = EbookFormatType::tryFrom(HttpInput::Str(GET, 'format') ?? '') ?? EbookFormatType::Epub;
	switch($format){
		case EbookFormatType::Kepub:
			$downloadUrl = $ebook->KepubUrl;
			break;

		case EbookFormatType::Azw3:
			$downloadUrl = $ebook->Azw3Url;
			break;

		case EbookFormatType::AdvancedEpub:
			$downloadUrl = $ebook->AdvancedEpubUrl;
			break;

		case EbookFormatType::Epub:
		default:
			$downloadUrl = $ebook->EpubUrl;
			break;
	}

	if(!$showThankYouPage){
		// Download the file directly, without showing the thank you page
		$downloadPath = WEB_ROOT . $downloadUrl;

		if(!is_file($downloadPath)){
			throw new Exceptions\InvalidFileException();
		}

		// Everything OK, serve the file using Apache.
		// The xsendfile Apache module tells Apache to serve the file, including not-modified or etag headers.
		// Much more efficient than reading it in PHP and outputting it that way.
		header('X-Sendfile: ' . $downloadPath);
		header('Content-Type: ' . $format->GetMimeType());
		header('Content-Disposition: attachment; filename="' . basename($downloadPath) . '"');
		exit();
	}

	// Increment local download count, expires in 2 weeks
	$downloadCount++;
	setcookie('download-count', (string)$downloadCount, ['expires' => intval((new DateTimeImmutable('+2 week'))->format('U')), 'path' => '/', 'domain' => SITE_DOMAIN, 'secure' => true, 'httponly' => false, 'samesite' => 'Lax']);
}
catch(Exceptions\InvalidFileException | Exceptions\EbookNotFoundException){
	Template::Emit404();
}
?><?= Template::Header(['downloadUrl' => $downloadUrl]) ?>
<main class="donate">
	<h1>Your download has started!</h1>
	<div class="thank-you-container">
		<picture>
			<? if($ebook->CoverImage2xAvifUrl !== null){ ?><source srcset="<?= $ebook->CoverImage2xAvifUrl ?> 2x, <?= $ebook->CoverImageAvifUrl ?> 1x" type="image/avif"/><? } ?>
			<source srcset="<?= $ebook->CoverImage2xUrl ?> 2x, <?= $ebook->CoverImageUrl ?> 1x" type="image/jpg"/>
			<img class="ebook" src="<?= $ebook->CoverImage2xUrl ?>" alt="The cover for the Standard Ebooks edition of <?= Formatter::EscapeHtml(strip_tags($ebook->TitleWithCreditsHtml)) ?>" property="schema:image" height="335" width="224"/>
		</picture>
		<div>
			<p class="stinger">Before you go...</p>
			<p>Will you <a href="/donate">make a donation</a> to help us further our mission of creating beautiful, free ebooks?</p>
			<p>It takes a team of highly-skilled volunteers hours to create and proof each of our ebooks. We couldn’t do it without the financial support of literature lovers like you. Any amount helps further our mission!</p>
			<p class="button-row center">
				<a href="/donate" class="button">Make a donation towards free literature</a>
			</p>
			<p class="stinger">Join the Patrons Circle</p>
			<p><a href="/donate#patrons-circle">Joining our Patrons Circle</a> gives you access to some awesome book-lover benefits:</p>
			<ul>
				<li>
					<p>Your name <a href="/about#patrons-circle">listed on our masthead</a>. (You can also remain anonymous if you prefer.)</p>
				</li>
				<li>
					<p>Access to our various <a href="/feeds">ebook feeds</a>:</p>
					<ul>
						<li>
							<p>Browse and download from the entire Standard Ebooks catalog directly in your ereading app using our <a href="/feeds/opds">OPDS feed</a>.</p>
						</li>
						<li>
							<p>Get notified of new ebooks in your news client with our <a href="/feeds/atom">Atom</a> or <a href="/feeds/rss">RSS</a> feeds.</p>
						</li>
						<li>
							<p>Parse and process the feeds to use our ebooks in your personal software projects.</p>
						</li>
					</ul>
				</li>
				<li>
					<p>Access to <a href="/bulk-downloads">bulk ebook downloads</a> to easily download whole collections of ebooks at once.</p>
				</li>
				<li>
					<p>The ability to submit a book for inclusion on our <a href="/contribute/wanted-ebooks">Wanted Ebooks list</a>, once per quarter. (Submissions must conform to our <a href="/contribute/collections-policy">collections policy</a> and are subject to approval.)</p>
				</li>
				<li>
					<p>The right to periodically vote on a selection from our <a href="/contribute/wanted-ebooks">Wanted Ebooks list</a> to choose an ebook for immediate production. The resulting ebook will be a permanent addition to our <a href="/ebooks">online catalog of free digital literature</a>.</p>
				</li>
			</ul>
			<p class="button-row center">
				<a href="/donate#patrons-circle" class="button">Join the S.E. Patrons Circle</a>
			</p>
		</div>
	</div>
</main>
<?= Template::Footer() ?>
