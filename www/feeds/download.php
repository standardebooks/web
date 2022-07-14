<?
require_once('Core.php');

use function Safe\preg_match;

// This page is blocked by HTTP Basic auth.
// Basic authorization is handled in Core.php. By the time we get here,
// a valid user has a session.

$path = HttpInput::Str(GET, 'path', false) ?? '';
$isUserAgentAllowed = false;

try{
	$path = '/feeds/' . $path;

	if(!is_file(WEB_ROOT . $path)){
		throw new Exceptions\InvalidFileException();
	}

	// Certain user agents may bypass login entirely
	if(isset($_SERVER['HTTP_USER_AGENT'])){
		$isUserAgentAllowed = (bool)Db::QueryInt('select count(*) from FeedUserAgents where instr(?, UserAgent) limit 1', [$_SERVER['HTTP_USER_AGENT']]);
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

	// Everything OK, serve the file using Apache.
	// The xsendfile Apache module tells Apache to serve the file, including not-modified or etag headers.
	// Much more efficient than reading it in PHP and outputting it that way.
	header('X-Sendfile: ' . WEB_ROOT . $path);

	if(preg_match('/^\/feeds\/opds/', $path)){
		header('Content-Type: application/atom+xml;profile=opds-catalog;kind=acquisition; charset=utf-8');

		if(preg_match('/\/index\.xml$/', $path)){
			header('Content-Type: application/atom+xml;profile=opds-catalog;kind=navigation; charset=utf-8');
		}
	}
	elseif(preg_match('/^\/feeds\/rss/', $path)){
		header('Content-Type: application/rss+xml');
	}
	elseif(preg_match('/^\/feeds\/atom/', $path)){
		header('Content-Type: application/atom+xml');
	}

	exit();
}
catch(Exceptions\LoginRequiredException $ex){
	header('WWW-Authenticate: Basic realm="Enter your Patrons Circle email address and leave the password empty."');
	http_response_code(401);
}
catch(Exceptions\InvalidPermissionsException $ex){
	http_response_code(403);
}
catch(Exceptions\InvalidFileException $ex){
	Template::Emit404();
}

// Print the login info page
include(WEB_ROOT . '/feeds/401.php');
