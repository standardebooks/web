<?
require_once('Core.php');
use function Safe\glob;
use function Safe\preg_replace;
use function Safe\sort;

// Redirect to the latest version of the manual

$currentManual = Manual::GetLatestVersion();

$url = HttpInput::GetString('url', true, '');
$url = preg_replace('|^/|ius', '', $url);
$url = preg_replace('|\.php$|ius', '', $url);
$url = preg_replace('|/$|ius', '', $url);

if($url != ''){
	$url = '/' . $url;
}

http_response_code(302);
header('Location: /manual/' . $currentManual . $url);
