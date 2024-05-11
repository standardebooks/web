<?
use function Safe\preg_match;

// This page is blocked by HTTP Basic auth.
// Basic authorization is handled in Core.php. By the time we get here,
// a valid user has a session.

$path = HttpInput::Str(HttpVariableSource::Get, 'path') ?? '';

try{
	$path = '/feeds/' . $path;

	if(!is_file(WEB_ROOT . $path)){
		throw new Exceptions\InvalidFileException();
	}

	// Access to the Atom/RSS new releases feed is open to the public
	$isNewReleasesFeed = false;
	if(preg_match('/^\/feeds\/(rss|atom)\/new-releases\.xml$/ius', $path)){
		$isNewReleasesFeed = true;
	}

	if(!$isNewReleasesFeed){
		// Certain user agents may bypass login entirely
		$isUserAgentAllowed = false;
		if(isset($_SERVER['HTTP_USER_AGENT'])){
			$isUserAgentAllowed = Db::QueryInt('
							SELECT count(*)
							from FeedUserAgents
							where instr(?, UserAgent)
							limit 1
						', [$_SERVER['HTTP_USER_AGENT']]);
		}

		if(!$isUserAgentAllowed){
			if($GLOBALS['User'] === null){
				throw new Exceptions\LoginRequiredException();
			}

			if(!preg_match('/\.xml$/ius', $path)){
				throw new Exceptions\InvalidPermissionsException();
			}

			if(!$GLOBALS['User']->Benefits->CanAccessFeeds){
				throw new Exceptions\InvalidPermissionsException();
			}
		}
	}

	// Everything OK, serve the file using Apache.
	// The xsendfile Apache module tells Apache to serve the file, including not-modified or etag headers.
	// Much more efficient than reading it in PHP and outputting it that way.
	header('X-Sendfile: ' . WEB_ROOT . $path);

	$http = new HTTP2();
	$mime = 'application/xml';

	// Decide on what content-type to serve via HTTP content negotation.
	// If the feed is viewed from a web browser, we will usuall serve application/xml as that's typically what's in the browser's Accept header.
	// If the Accept header has application/rss+xml or application/atom+xml then serve that instead, as those are the
	// "technically correct" content types that may be requested by RSS readers.
	if(preg_match('/^\/feeds\/opds/', $path)){
		$contentType = [
			'application/atom+xml',
			'application/xml',
			'text/xml'
		];
		$mime = $http->negotiateMimeType($contentType,  'application/atom+xml');

		if($mime == 'application/atom+xml'){
			if(preg_match('/\/index\.xml$/', $path)){
				$mime .= ';profile=opds-catalog;kind=navigation; charset=utf-8';
			}
			else{
				$mime .= ';profile=opds-catalog;kind=acquisition; charset=utf-8';
			}
		}
	}
	elseif(preg_match('/^\/feeds\/rss/', $path)){
		$contentType = [
			'application/rss+xml',
			'application/xml',
			'text/xml'
		];
		$mime = $http->negotiateMimeType($contentType,  'application/rss+xml');
	}
	elseif(preg_match('/^\/feeds\/atom/', $path)){
		$contentType = [
			'application/atom+xml',
			'application/xml',
			'text/xml'
		];
		$mime = $http->negotiateMimeType($contentType,  'application/atom+xml');
	}

	header('Content-Type: ' . $mime);

	exit();
}
catch(Exceptions\LoginRequiredException){
	header('WWW-Authenticate: Basic realm="Enter your Patrons Circle email address and leave the password empty."');
	http_response_code(401);
}
catch(Exceptions\InvalidPermissionsException){
	http_response_code(403);
}
catch(Exceptions\InvalidFileException){
	Template::Emit404();
}

// Print the login info page
include(WEB_ROOT . '/feeds/401.php');
