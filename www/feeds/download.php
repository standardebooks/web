<?
/**
 * GET		/feeds/:feed-format/:path
 */

use function Safe\preg_match;
use function Safe\preg_replace;

// This page is blocked by HTTP Basic auth.
// Basic authorization is handled in `Core.php`. By the time we get here, a valid user has a session.

try{
	$path = '/feeds/' . (Http::$Request->QueryString->Get('path') ?? '');

	// Remove `./` and `../` from the path.
	$path = preg_replace('/\.\.?\//u', '', $path);
	$relativePath = $path;

	$targetMimeType = Feed::NegotiateMimeType($relativePath);

	if(preg_match('/^\/feeds\/opds/ius', $relativePath)){
		$relativePath = OpdsFeed::GetPath($relativePath, $targetMimeType);
	}

	$path = WEB_ROOT . $relativePath;

	if(!is_file($path) || !preg_match('/^' . preg_quote(WEB_ROOT . '/feeds/', '/') . '.+\.(xml|json)$/iu', $path)){
		throw new Exceptions\FileInvalidException();
	}

	// Access to the Atom/RSS new releases feed is open to the public.
	$isNewReleasesFeed = false;
	if(preg_match('/^\/feeds\/(rss|atom)\/new-releases\.xml$/ius', $relativePath)){
		$isNewReleasesFeed = true;
	}

	if(!$isNewReleasesFeed){
		// Certain user agents may bypass login entirely.
		$isUserAgentAllowed = false;
		$userAgent = Http::$Request->Headers['user-agent'] ?? null;
		if(isset($userAgent)){
			$isUserAgentAllowed = Db::QueryBool('
							SELECT exists(
								select *
								from FeedUserAgents
								where instr(?, UserAgent)
							)
						', [$userAgent]);
		}

		if(!$isUserAgentAllowed){
			if(Session::$User === null){
				throw new Exceptions\LoginRequiredException();
			}

			if(!Session::$User->Benefits->CanAccessFeeds){
				throw new Exceptions\PermissionsInvalidException();
			}
		}
	}

	// Everything OK, serve the file using Apache.
	// The `xsendfile` Apache module tells Apache to serve the file, including `not-modified` or `etag` headers.
	// Much more efficient than reading it in PHP and outputting it that way.
	header('x-sendfile: ' . $path);

	header('content-type: ' . $targetMimeType);

	exit();
}
catch(Exceptions\FileInvalidException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	header('www-authenticate: Basic realm="Enter your Patrons Circle email address and leave the password empty."');
	http_response_code(Enums\HttpCode::Unauthorized->value);
}
catch(Exceptions\PermissionsInvalidException){
	http_response_code(Enums\HttpCode::Forbidden->value);
}

// Print the login info page.
include(WEB_ROOT . '/feeds/401.php');
