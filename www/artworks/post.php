<? /** @noinspection PhpComposerExtensionStubsInspection, PhpIncludeInspection */

use Ramsey\Uuid\Uuid;

require_once('Core.php');

/**
 * @return array<string> an array containing [0] the path to the uploaded image and [1] the path to the thumbnail
 * @throws \Exceptions\InvalidImageUploadException
 */
function handleImageUpload($uploadTmp): array{
	$uploadInfo = getimagesize($uploadTmp);

	if ($uploadInfo === false){
		throw new Exceptions\InvalidImageUploadException();
	}

	if ($uploadInfo[2] !== IMAGETYPE_GIF && $uploadInfo[2] !== IMAGETYPE_JPEG && $uploadInfo[2] !== IMAGETYPE_PNG){
		throw new Exceptions\InvalidImageUploadException();
	}

	$uid = Uuid::uuid4()->toString();
	$ext = image_type_to_extension($uploadInfo[2]);

	$uploadPath = COVER_ART_UPLOAD_PATH . $uid . $ext;
	$thumbPath = COVER_ART_UPLOAD_PATH . $uid . '.thumb' . $ext;

	if (!move_uploaded_file($uploadTmp, $uploadPath)){
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

	switch ($uploadInfo[2]){
		case IMAGETYPE_GIF:
			$srcImage = imagecreatefromgif($uploadPath);
			$writeFn = 'imagegif';
			break;
		case IMAGETYPE_PNG:
			$srcImage = imagecreatefrompng($uploadPath);
			$writeFn = 'imagepng';
			break;
		case IMAGETYPE_JPEG:
		default:
			$srcImage = imagecreatefromjpeg($uploadPath);
			$writeFn = 'imagejpeg';

			if (!$srcImage){
				throw new \Exceptions\InvalidImageUploadException();
			}
	}

	$thumbImage = imagecreatetruecolor($dst_w, $dst_h);
	imagecopyresampled($thumbImage, $srcImage, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
	$writeFn($thumbImage, $thumbPath);

	return [$uploadPath, $thumbPath];
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
	$expectCaptcha = HttpInput::Str(SESSION, 'captcha', false);
	$actualCaptcha = HttpInput::Str(POST, 'captcha', false);

	if ($expectCaptcha === '' || mb_strtolower($expectCaptcha) !== mb_strtolower($actualCaptcha)){
		throw new Exceptions\InvalidCaptchaException();
	}

	$artistName = HttpInput::Str(POST, 'artist-name', false);
	$artistYearOfDeath = HttpInput::Int(POST, 'artist-year-of-death');

	$artist = Artist::GetOrCreate($artistName, $artistYearOfDeath);

	$artwork = new Artwork();

	$imageUpload = handleImageUpload($_FILES['color-upload']['tmp_name']);

	$artwork->Artist = $artist;
	$artwork->Name = HttpInput::Str(POST, 'artwork-name', false);
	$artwork->CompletedYear = HttpInput::Str(POST, 'artwork-year', false);
	$artwork->CompletedYearIsCirca = HttpInput::Bool(POST, 'artwork-year-is-circa', false);
	$artwork->ArtworkTags = parseArtworkTags();
	$artwork->ImageFilesystemPath = $imageUpload[0];
	$artwork->Created = new DateTime();
	$artwork->Status = 'unverified';

	$artwork->MuseumPage = HttpInput::Str(POST, 'pd-proof-museum-link', false);
	$artwork->PublicationYear = HttpInput::Int(POST, 'pd-proof-year-of-publication');
	$artwork->PublicationYearPage = HttpInput::Str(POST, 'pd-proof-year-of-publication-page', false);
	$artwork->CopyrightPage = HttpInput::Str(POST, 'pd-proof-copyright-page', false);
	$artwork->ArtworkPage = HttpInput::Str(POST, 'pd-proof-artwork-page', false);

	$artwork->Create();

	$_SESSION['successfully-submitted-artwork'] = true;
	http_response_code(303);
	header('Location: ' . "/artworks/new");

} catch (\Exceptions\SeException $exception){
	$_SESSION['exception'] = $exception;

	if (isset($imageUpload)){
		// clean up the uploaded file(s)
		unlink($imageUpload[0]);
		unlink($imageUpload[1]);
	}

	http_response_code(303);
	header('Location: /artworks/new');
}
