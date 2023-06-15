<? /** @noinspection PhpIncludeInspection */

use Ramsey\Uuid\Uuid;

require_once('Core.php');

/**
 * @return string the path to move the uploaded image to
 * @throws \Exceptions\InvalidImageUploadException
 */
function handleImageUpload($uploadTmp): string {
	$uploadInfo = getimagesize($uploadTmp);

	if ($uploadInfo === false) {
		throw new Exceptions\InvalidImageUploadException();
	}

	$uploadUuid = Uuid::uuid4()->toString();
	$uploadExt = image_type_to_extension($uploadInfo[2]);

	$uploadPath = COVER_ART_UPLOAD_PATH . $uploadUuid . $uploadExt;

	if (!move_uploaded_file($uploadTmp, $uploadPath)) {
		throw new Exceptions\InvalidImageUploadException();
	}

	return $uploadPath;
}

/**
 * @return array<ArtworkTag>
 * @throws \Exceptions\InvalidArtworkTagException
 */
function parseArtworkTags(): array {
	$artworkTags = HttpInput::Str(POST, 'artwork-tags', false);
	$artworkTags = array_map('trim', explode(',', $artworkTags)) ?? array();
	$artworkTags = array_unique($artworkTags);

	return array_map(function ($artworkTag) {
		return ArtworkTag::GetOrCreate($artworkTag);
	}, $artworkTags);
}

if (HttpInput::RequestMethod() != HTTP_POST) {
	http_response_code(405);
	exit();
}

session_start();

try {
	$expectCaptcha = HttpInput::Str(SESSION, 'captcha', false);
	$actualCaptcha = HttpInput::Str(POST, 'captcha', false);

	if ($expectCaptcha === '' || mb_strtolower($expectCaptcha) !== mb_strtolower($actualCaptcha)) {
		throw new Exceptions\InvalidCaptchaException();
	}

	$artistName = HttpInput::Str(POST, 'artist-name', false);
	$artistYearOfDeath = HttpInput::Int(POST, 'artist-year-of-death');

	$artist = Artist::GetOrCreate($artistName, $artistYearOfDeath);

	$artwork = new Artwork();

	$artwork->Artist = $artist;
	$artwork->Name = HttpInput::Str(POST, 'artwork-name', false);
	$artwork->CompletedYear = HttpInput::Str(POST, 'artwork-year', false);
	$artwork->CompletedYearIsCirca = HttpInput::Bool(POST, 'artwork-year-is-circa', false);
	$artwork->ArtworkTags = parseArtworkTags();
	$artwork->ImageFilesystemPath = handleImageUpload($_FILES['color-upload']['tmp_name']);
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

} catch (\Exceptions\SeException $exception) {
	$_SESSION['exception'] = $exception;

	if (isset($uploadPath)) {
		// clean up the uploaded file
		unlink($uploadPath);
	}

	http_response_code(303);
	header('Location: /artworks/new');
}
