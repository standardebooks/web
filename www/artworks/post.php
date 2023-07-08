<? /** @noinspection PhpComposerExtensionStubsInspection, PhpIncludeInspection */

require_once('Core.php');

/**
 * @throws \Exceptions\InvalidImageUploadException
 */
function handleImageUpload(string $uploadTmp, Artwork $artwork): void{
	$uploadInfo = getimagesize($uploadTmp);

	if ($uploadInfo === false){
		throw new Exceptions\InvalidImageUploadException();
	}

	if ($uploadInfo[2] !== IMAGETYPE_JPEG){
		throw new Exceptions\InvalidImageUploadException('Uploaded image must be a JPG file.');
	}

	$imagePath = WEB_ROOT . $artwork->ImageUrl;
	$thumbPath = WEB_ROOT . $artwork->ThumbUrl;

	if (!move_uploaded_file($uploadTmp, $imagePath)){
		throw new Exceptions\InvalidImageUploadException();
	}

	$src_w = $uploadInfo[0];
	$src_h = $uploadInfo[1];

	if ($src_h > $src_w){
		$dst_h = COVER_THUMBNAIL_SIZE;
		$dst_w = intval($dst_h * ($src_w / $src_h));
	} else{
		$dst_w = COVER_THUMBNAIL_SIZE;
		$dst_h = intval($dst_w * ($src_h / $src_w));
	}

	$srcImage = imagecreatefromjpeg($imagePath);
	$thumbImage = imagecreatetruecolor($dst_w, $dst_h);

	imagecopyresampled($thumbImage, $srcImage, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
	imagejpeg($thumbImage, $thumbPath);
}

/**
 * @return array<ArtworkTag>
 * @throws \Exceptions\InvalidArtworkTagException
 */
function parseArtworkTags(): array{
	$artworkTags = HttpInput::Str(POST, 'artwork-tags', false);

	if (!$artworkTags){
		return array();
	}

	$artworkTags = array_map('trim', explode(',', $artworkTags)) ?? array();
	$artworkTags = array_values(array_filter($artworkTags)) ?? array();
	$artworkTags = array_unique($artworkTags);

	return array_map(function ($artworkTag){
		return ArtworkTag::GetOrCreate($artworkTag);
	}, $artworkTags);
}

if (HttpInput::RequestMethod() != HTTP_POST){
	http_response_code(405);
	exit();
}

session_start();

try{
	$artist = new Artist();
	$artist->Name = HttpInput::Str(POST, 'artist-name', false);
	$artist->DeathYear = HttpInput::Int(POST, 'artist-year-of-death');

	$artwork = new Artwork();

	$artwork->Artist = $artist;
	$artwork->Name = HttpInput::Str(POST, 'artwork-name', false);
	$artwork->CompletedYear = HttpInput::Str(POST, 'artwork-year', false);
	$artwork->CompletedYearIsCirca = HttpInput::Bool(POST, 'artwork-year-is-circa', false);
	$artwork->ArtworkTags = parseArtworkTags();
	$artwork->Created = new DateTime();
	$artwork->Status = 'unverified';

	$artwork->MuseumPage = HttpInput::Str(POST, 'pd-proof-museum-link', false);
	$artwork->PublicationYear = HttpInput::Int(POST, 'pd-proof-year-of-publication');
	$artwork->PublicationYearPage = HttpInput::Str(POST, 'pd-proof-year-of-publication-page', false);
	$artwork->CopyrightPage = HttpInput::Str(POST, 'pd-proof-copyright-page', false);
	$artwork->ArtworkPage = HttpInput::Str(POST, 'pd-proof-artwork-page', false);

	$expectCaptcha = HttpInput::Str(SESSION, 'captcha', false);
	$actualCaptcha = HttpInput::Str(POST, 'captcha', false);

	if ($expectCaptcha === '' || mb_strtolower($expectCaptcha) !== mb_strtolower($actualCaptcha)){
		throw new Exceptions\InvalidCaptchaException();
	}

	$artist->GetOrCreate();
	$artwork->Create();

	handleImageUpload($_FILES['color-upload']['tmp_name'], $artwork);

	$_SESSION['success-message'] = '“' . Formatter::ToPlainText($artwork->Name) . '” submitted successfully!';
	http_response_code(303);
	header('Location: ' . '/artworks/new');

} catch (\Exceptions\SeException $exception){
	$_SESSION['exception'] = $exception;

	if (isset($artwork)){
		$_SESSION['artwork'] = $artwork;
	}

	if (isset($artwork->ArtworkId)){
		// clean up the uploaded file(s)
		unlink(WEB_ROOT . $artwork->ImageUrl);
		unlink(WEB_ROOT . $artwork->ThumbUrl);

		// remove database entry
		$artwork->Delete();
	}

	if (isset($artist->ArtistId)){
		$artist->DeleteIfUnused();
	}

	http_response_code(303);
	header('Location: /artworks/new');
}
