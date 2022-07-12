<?
// Composer auto-loads the lib/ directory in composer.json
require __DIR__ . '/../vendor/autoload.php';

use function Safe\mb_internal_encoding;
use function Safe\mb_http_output;
use function Safe\error_log;

mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
date_default_timezone_set('UTC');

// Custom error handler to output more details about the specific Apache request that caused an exception.
if(SITE_STATUS == SITE_STATUS_LIVE){
	set_exception_handler(function(Throwable $ex): void{
		$errorString = "----------------------------------------\n";
		$errorString .= trim(vds(array_intersect_key($_SERVER, ['REQUEST_URI' => '', 'QUERY_STRING' => '', 'REQUEST_METHOD' => '', 'REDIRECT_QUERY_STRING' => '', 'REDIRECT_URL' => '', 'SCRIPT_FILENAME' => '', 'REMOTE_ADDR' => '', 'HTTP_COOKIE' => '', 'HTTP_USER_AGENT' => '', 'SCRIPT_URI' => ''])));

		if(sizeof($_POST) > 0){
			$errorString .= "POST DATA:\n";
			$errorString .= vds($_POST);
		}

		error_log($errorString);

		throw $ex; // Send the exception back to PHP for its usual logging routine.
	});
}

$GLOBALS['User'] = Session::GetLoggedInUser();

if($GLOBALS['User'] === null){
	$httpBasicAuthLogin = $_SERVER['PHP_AUTH_USER'] ?? null;

	if($httpBasicAuthLogin !== null){
		// If there's no logged in user, but a username was sent via HTTP basic auth,
		// log them in while we're here.

		$session = new Session();
		$session->Create($httpBasicAuthLogin);
	}
}
