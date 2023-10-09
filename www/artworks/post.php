<? /** @noinspection PhpIncludeInspection */

require_once('Core.php');

use function Safe\ini_get;
use function Safe\substr;

function post_max_size_bytes(): int{
	$post_max_size = ini_get('post_max_size');
	$unit = substr($post_max_size, -1);
	$size = (int) substr($post_max_size, 0, -1);

	return match ($unit) {
		'g', 'G' => $size * 1024 * 1024 * 1024,
		'm', 'M' => $size * 1024 * 1024,
		'k', 'K' => $size * 1024,
		default => $size
	};
}

if(HttpInput::RequestMethod() != HTTP_POST){
	http_response_code(405);
	exit();
}

session_start();

try{
	if(empty($_POST) || empty($_FILES)){
		if($_SERVER['CONTENT_LENGTH'] > post_max_size_bytes()){
			throw new \Exceptions\InvalidRequestException('Request too large (maximum ' . ini_get('upload_max_filesize') . ')');
		}else{
			throw new \Exceptions\InvalidRequestException();
		}
	}

	$artistName = HttpInput::Str(POST, 'artist-name', false);
	if($artistName === null){
		throw new \Exceptions\InvalidRequestException('Empty or invalid Artist Name');
	}

	$artworkName = HttpInput::Str(POST, 'artwork-name', false);
	if($artworkName === null){
		throw new \Exceptions\InvalidRequestException('Empty or invalid Artwork Name');
	}

	$artwork = Artwork::Build(
		artistName: $artistName,
		artistDeathYear: HttpInput::Int(POST, 'artist-year-of-death'),
		artworkName: $artworkName,
		completedYear: HttpInput::Int(POST, 'artwork-year'),
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

	if($expectCaptcha === null || $actualCaptcha === null || mb_strtolower($expectCaptcha) !== mb_strtolower($actualCaptcha)){
		throw new Exceptions\InvalidCaptchaException();
	}

	$uploadError = $_FILES['color-upload']['error'];
	if($uploadError > UPLOAD_ERR_OK){
		// see https://www.php.net/manual/en/features.file-upload.errors.php
		$message = match ($uploadError){
			UPLOAD_ERR_INI_SIZE => 'Image upload too large (maximum ' . ini_get('upload_max_filesize') . ')',
			default => 'Image failed to upload (error code ' . $uploadError . ')',
		};

		throw new \Exceptions\InvalidImageUploadException($message);
	}

	$artwork->Create($_FILES['color-upload']['tmp_name']);

	$_SESSION['success-message'] = '“' . $artwork->Name . '” submitted successfully!';

} catch (\Exceptions\AppException $exception){
	$_SESSION['exception'] = $exception;

	if(isset($artwork)){
		$_SESSION['artwork'] = $artwork;
	}

} finally{
	http_response_code(303);
	header('Location: /artworks/new');
}
