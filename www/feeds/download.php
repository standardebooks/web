<?
/**
 * GET		/feeds/:feed-format/:path
 */

use function Safe\preg_match;
use function Safe\preg_replace;

// This page is blocked by HTTP Basic auth.
// Basic authorization is handled in `Core.php`. By the time we get here, a valid user has a session.

try{
	$path = '/feeds/' . (HttpInput::Str(GET, 'path') ?? '');

	// Remove `./` and `../` from the path.
	$path = preg_replace('/\.\.?\//u', '', $path);
	$relativePath = $path;
	$path = WEB_ROOT . $path;

	if(!is_file($path) || !preg_match('/^' . preg_quote(WEB_ROOT . '/feeds/', '/') . '.+\.xml$/iu', $path)){
		throw new Exceptions\InvalidFileException();
	}

	// Access to the Atom/RSS new releases feed is open to the public.
	$isNewReleasesFeed = false;
	if(preg_match('/^\/feeds\/(rss|atom)\/new-releases\.xml$/ius', $relativePath)){
		$isNewReleasesFeed = true;
	}

	if(!$isNewReleasesFeed){
		// Certain user agents may bypass login entirely.
		$isUserAgentAllowed = false;
		if(isset($_SERVER['HTTP_USER_AGENT'])){
			$isUserAgentAllowed = Db::QueryBool('
							SELECT exists(
								select *
								from FeedUserAgents
								where instr(?, UserAgent)
							)
						', [$_SERVER['HTTP_USER_AGENT']]);
		}

		if(!$isUserAgentAllowed){
			if(Session::$User === null){
				throw new Exceptions\LoginRequiredException();
			}

			if(!Session::$User->Benefits->CanAccessFeeds){
				throw new Exceptions\InvalidPermissionsException();
			}
		}
	}

	// Everything OK, serve the file using Apache.
	// The `xsendfile` Apache module tells Apache to serve the file, including `not-modified` or `etag` headers.
	// Much more efficient than reading it in PHP and outputting it that way.
	header('x-sendfile: ' . $path);

	$http = new HTTP2();
	$mime = 'application/xml';

	// Decide on what content-type to serve via HTTP content negotation.
	// If the feed is viewed from a web browser, we will usually serve `application/xml` as that's typically what's in the browser's `Accept` header.
	// If the `Accept` header is `application/rss+xml` or `application/atom+xml` then serve that instead, as those are the "technically correct" content types that may be requested by RSS readers.
	if(preg_match('/^\/feeds\/opds/', $relativePath)){
		$contentType = [
			'application/atom+xml',
			'application/xml',
			'text/xml'
		];
		$mime = $http->negotiateMimeType($contentType, 'application/atom+xml');

		if($mime == 'application/atom+xml'){
			if(preg_match('/\/index\.xml$/', $relativePath)){
				$mime .= ';profile=opds-catalog;kind=navigation; charset=utf-8';
			}
			else{
				$mime .= ';profile=opds-catalog;kind=acquisition; charset=utf-8';
			}
		}
	}
	elseif(preg_match('/^\/feeds\/rss/', $relativePath)){
		$contentType = [
			'application/rss+xml',
			'application/xml',
			'text/xml'
		];
		$mime = $http->negotiateMimeType($contentType, 'application/rss+xml');
	}
	elseif(preg_match('/^\/feeds\/atom/', $relativePath)){
		$contentType = [
			'application/atom+xml',
			'application/xml',
			'text/xml'
		];
		$mime = $http->negotiateMimeType($contentType, 'application/atom+xml');
	}
	elseif(preg_match('/^\/feeds\/onix/', $relativePath)){
		$contentType = [
			'application/onix+xml',
			'application/xml',
			'text/xml'
		];
		$mime = $http->negotiateMimeType($contentType, 'application/onix+xml');
	}

	header('content-type: ' . $mime);

	exit();
}
catch(Exceptions\InvalidFileException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	header('www-authenticate: Basic realm="Enter your Patrons Circle email address and leave the password empty."');
	http_response_code(Enums\HttpCode::Unauthorized->value);
}
catch(Exceptions\InvalidPermissionsException){
	http_response_code(Enums\HttpCode::Forbidden->value);
}

// Print the login info page.
include(WEB_ROOT . '/feeds/401.php');
