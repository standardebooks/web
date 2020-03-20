<?
require_once('Core.php');

// Redirect to the latest version of the manual

$dirs = glob(MANUAL_PATH . '/*', GLOB_ONLYDIR);
sort($dirs);

$currentManual = str_replace(WEB_ROOT, '', $dirs[sizeof($dirs) - 1]);

http_response_code(302);
header('Location: ' . $currentManual);
