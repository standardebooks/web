<?
use Safe\DateTimeImmutable;


$ebook = null;
$downloadUrl = null;
$downloadCount = HttpInput::Int(COOKIE, 'download-count') ?? 0;

// The download source is set in feed links and meta refresh links. It is `null` on links from `www/ebooks/get.php`.
$source = Enums\EbookDownloadSource::tryFrom(HttpInput::Str(GET, 'source') ?? '');

// Skip the thank you page if any of these are true:
// - The user is logged in.
// - Their `download-count` cookie is above some amount.
// - The link is from a specific source.
$skipThankYouPage = isset(Session::$User) || $downloadCount > 4 || isset($source);

// `$source` will be `null` for users that skip the download page because they are logged in or because of their `download-count` cookie, but they are downloading from the web so that's how their download should be recorded.
if($skipThankYouPage && !isset($source)){
	$source = Enums\EbookDownloadSource::DownloadPage;
}

try{
	$urlPath = HttpInput::Str(GET, 'url-path') ?? null;
	$identifier = EBOOKS_IDENTIFIER_PREFIX . $urlPath;
	$ebook = Ebook::GetByIdentifier($identifier);

	if($ebook->IsPlaceholder()){
		throw new Exceptions\InvalidFileException();
	}

	$filename = HttpInput::Str(GET, 'filename');
	if(!isset($filename)){
		throw new Exceptions\InvalidFileException();
	}

	try{
		$format = Enums\EbookFormatType::FromFilename($filename);
	}
	catch(Exceptions\InvalidEbookFormatException){
		throw new Exceptions\InvalidFileException();
	}

	/** @var string|null $ipAddress */
	$ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;

	/** @var string|null $userAgent */
	$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;

	if(!isset(Session::$User) && isset($ipAddress)){
		$limitExceeded = false;

		// Check for excessive downloads.
		$shortDownloadCount = EbookDownload::GetCountByIpAddressSince($ipAddress, NOW->modify('-30 seconds'));
		if($shortDownloadCount > SHORT_DOWNLOAD_COUNT){
			$limitExceeded = true;
		}
		else{
			$longDownloadCount = EbookDownload::GetCountByIpAddressSince($ipAddress, NOW->modify('-6 hour'));
			if($longDownloadCount > LONG_DOWNLOAD_COUNT){
				$limitExceeded = true;
			}
		}

		if($limitExceeded){
			$rateLimitedIp = new RateLimitedIp();
			$rateLimitedIp->IpAddress = $ipAddress;
			$rateLimitedIp->Create();
			Template::ExitWithCode(Enums\HttpCode::TooManyRequests);
		}
	}

	if($skipThankYouPage){
		// Download the file directly, without showing the thank you page.
		$downloadUrl = $ebook->GetDownloadUrl($format);
		$downloadPath = WEB_ROOT . $downloadUrl;

		if(!is_file($downloadPath)){
			throw new Exceptions\InvalidFileException();
		}

		try{
			$ebook->AddDownload($ipAddress, $userAgent, $source);
		}
		catch(Exceptions\InvalidEbookDownloadException){
			// Pass. Allow the download to continue even if it isn't recorded.
		}

		// Everything OK, serve the file using Apache.
		// The `xsendfile` Apache module tells Apache to serve the file, including `not-modified` or `etag` headers.
		// Much more efficient than reading it in PHP and outputting it that way.
		header('X-Sendfile: ' . $downloadPath);
		header('Content-Type: ' . $format->GetMimeType());
		header('Content-Disposition: attachment; filename="' . basename($downloadPath) . '"');
		exit();
	}

	$downloadUrl = $ebook->GetDownloadUrl($format, Enums\EbookDownloadSource::DownloadPage);

	// Increment local download count, expires in 2 weeks.
	$downloadCount++;
	setcookie('download-count', (string)$downloadCount, ['expires' => intval((new DateTimeImmutable('+2 week'))->format(Enums\DateTimeFormat::UnixTimestamp->value)), 'path' => '/', 'domain' => SITE_DOMAIN, 'secure' => true, 'httponly' => false, 'samesite' => 'Lax']);
}
catch(Exceptions\InvalidFileException | Exceptions\EbookNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
?><?= Template::Header(title: 'Your Download Has Started!', downloadUrl: $downloadUrl) ?>
<main class="donate">
	<h1>Your Download Has Started!</h1>
	<div class="thank-you-container">
		<picture>
			<? if($ebook->CoverImage2xAvifUrl !== null){ ?><source srcset="<?= $ebook->CoverImage2xAvifUrl ?> 2x, <?= $ebook->CoverImageAvifUrl ?> 1x" type="image/avif"/><? } ?>
			<source srcset="<?= $ebook->CoverImage2xUrl ?> 2x, <?= $ebook->CoverImageUrl ?> 1x" type="image/jpg"/>
			<img class="ebook" src="<?= $ebook->CoverImage2xUrl ?>" alt="The cover for the Standard Ebooks edition of <?= Formatter::EscapeHtml(strip_tags($ebook->TitleWithCreditsHtml)) ?>" property="schema:image" height="335" width="224"/>
		</picture>
		<div>
			<?= Template::DonationCounter() ?>
			<?= Template::DonationProgress() ?>

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
