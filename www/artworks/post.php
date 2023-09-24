<? /** @noinspection PhpIncludeInspection */

require_once('Core.php');

if (HttpInput::RequestMethod() != HTTP_POST){
	http_response_code(405);
	exit();
}

session_start();

try{
	$artwork = Artwork::Build(
		artistName: HttpInput::Str(POST, 'artist-name', false),
		artistDeathYear: HttpInput::Int(POST, 'artist-year-of-death'),
		artworkName: HttpInput::Str(POST, 'artwork-name', false),
		completedYear: HttpInput::Str(POST, 'artwork-year', false),
		completedYearIsCirca: HttpInput::Bool(POST, 'artwork-year-is-circa', false),
		artworkTags: HttpInput::Str(POST, 'artwork-tags', false),
		publicationYear: HttpInput::Int(POST, 'pd-proof-year-of-publication'),
		publicationYearPage: HttpInput::Str(POST, 'pd-proof-year-of-publication-page', false),
		copyrightPage: HttpInput::Str(POST, 'pd-proof-copyright-page', false),
		artworkPage: HttpInput::Str(POST, 'pd-proof-artwork-page', false),
		museumPage: HttpInput::Str(POST, 'pd-proof-museum-link', false),
	);

	$expectCaptcha = HttpInput::Str(SESSION, 'captcha', false);
	$actualCaptcha = HttpInput::Str(POST, 'captcha', false);

	if ($expectCaptcha === '' || mb_strtolower($expectCaptcha) !== mb_strtolower($actualCaptcha)){
		throw new Exceptions\InvalidCaptchaException();
	}

	if ($_FILES['color-upload']['error'] > 0){
		// see https://www.php.net/manual/en/features.file-upload.errors.php
		$message = match ($_FILES['color-upload']['error']){
			1 => 'Image upload too large (maximum ' . ini_get('upload_max_filesize') . ')',
			default => 'Image failed to upload',
		};

		throw new \Exceptions\InvalidImageUploadException($message);
	}

	$artwork->Create($_FILES['color-upload']['tmp_name']);

	$_SESSION['success-message'] = '“' . $artwork->Name . '” submitted successfully!';

} catch (\Exceptions\SeException $exception){
	$_SESSION['exception'] = $exception;

	if (isset($artwork)){
		$_SESSION['artwork'] = $artwork;
	}

} finally{
	http_response_code(303);
	header('Location: /artworks/new');
}
