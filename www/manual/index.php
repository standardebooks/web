<?
require_once('Core.php');
use function Safe\glob;
use function Safe\preg_replace;
use function Safe\sort;

// Redirect to the latest version of the manual

$dirs = glob(MANUAL_PATH . '/*', GLOB_ONLYDIR);
sort($dirs);

$currentManual = str_replace(WEB_ROOT, '', $dirs[sizeof($dirs) - 1]);

$url = HttpInput::GetString('url', true, '');
$url = preg_replace('|^/|ius', '', $url);
$url = preg_replace('|\.php$|ius', '', $url);
$url = preg_replace('|/$|ius', '', $url);

if($url != ''){
	$url = '/' . $url;
}

http_response_code(302);
header('Location: ' . $currentManual . $url);
