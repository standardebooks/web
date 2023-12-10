<? /** @noinspection PhpIncludeInspection */

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

	$artist = new Artist();
	$artist->Name = HttpInput::Str(POST, 'artist-name', false);
	$artist->DeathYear = HttpInput::Int(POST, 'artist-year-of-death');

	$artwork = new Artwork();
	$artwork->Artist = $artist;
	$artwork->Name = HttpInput::Str(POST, 'artwork-name', false);
	$artwork->CompletedYear = HttpInput::Int(POST, 'artwork-year');
	$artwork->CompletedYearIsCirca = HttpInput::Bool(POST, 'artwork-year-is-circa', false);
	$artwork->ArtworkTags = Artwork::ParseArtworkTags(HttpInput::Str(POST, 'artwork-tags', false));
	$artwork->Status = COVER_ARTWORK_STATUS_UNVERIFIED;
	$artwork->PublicationYear = HttpInput::Int(POST, 'pd-proof-publication-year');
	$artwork->PublicationYearPageUrl = HttpInput::Str(POST, 'pd-proof-publication-year-page-url', false);
	$artwork->CopyrightPageUrl = HttpInput::Str(POST, 'pd-proof-copyright-page-url', false);
	$artwork->ArtworkPageUrl = HttpInput::Str(POST, 'pd-proof-artwork-page-url', false);
	$artwork->MuseumUrl = HttpInput::Str(POST, 'pd-proof-museum-url', false);

	$expectCaptcha = HttpInput::Str(SESSION, 'captcha', false);
	$actualCaptcha = HttpInput::Str(POST, 'captcha', false);

	if($expectCaptcha === null || $actualCaptcha === null || mb_strtolower($expectCaptcha) !== mb_strtolower($actualCaptcha)){
		throw new Exceptions\InvalidCaptchaException();
	}

	$artwork->Create($_FILES['color-upload']);

	$_SESSION['success-message'] = '“' . $artwork->Name . '” submitted successfully!';
}
catch(\Exceptions\AppException $exception){
	$_SESSION['exception'] = $exception;

	if(isset($artwork)){
		$_SESSION['artwork'] = $artwork;
	}
}
finally{
	http_response_code(303);
	header('Location: /artworks/new');
}
