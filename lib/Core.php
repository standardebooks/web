<?
// Composer auto-loads the `lib/` directory in `composer.json`.
require __DIR__ . '/../vendor/autoload.php';

use function Safe\error_log;
use function Safe\mb_internal_encoding;
use function Safe\mb_http_output;
use function Safe\ob_end_clean;
use function Safe\ob_start;

mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
date_default_timezone_set('UTC');

/**
 * Convenient shorthand alias of `var_dump()`.
 *
 * @param mixed $var The variable to dump.
 */
function vd(mixed $var): void{
	var_dump($var);
}

/**
 * Convenient shorthand alias to `var_dump()`, then `die()`.
 *
 * @param mixed $var The variable to dump.
 */
function vdd(mixed $var): void{
	var_dump($var);
	die();
}

/**
 * `var_dump()` into a string.
 *
 * @param mixed $var The variable to dump into a string.
 *
 * @return string The output of `var_dump()`.
 */
function vds(mixed $var): string{
	ob_start();
	var_dump($var);
	$str = ob_get_contents();
	if($str === false){
		$str = '';
	}
	ob_end_clean();
	return $str;
}

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

$GLOBALS['DbConnection'] = new DbConnection(DATABASE_DEFAULT_DATABASE, DATABASE_DEFAULT_HOST);

Session::InitializeFromCookie();

if(Session::$User === null){
	$httpBasicAuthLogin = $_SERVER['PHP_AUTH_USER'] ?? null;

	if($httpBasicAuthLogin !== null){
		// If there's no logged in user, but a username was sent via HTTP basic auth, log them in while we're here.

		$session = new Session();
		try{
			$password = $_SERVER['PHP_AUTH_PW'] ?? null;
			if($password == ''){
				$password = null;
			}

			// Most patrons have a `null` password, meaning they only need to log in using an email and a blank password.
			// Some users with admin rights need a password to log in.
			$session->Create($httpBasicAuthLogin, $password);
		}
		catch(Exception){
			// Do nothing.
		}
	}
}
