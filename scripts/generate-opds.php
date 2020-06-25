<?
require_once('/standardebooks.org/web/lib/Core.php');

use function Safe\krsort;
use function Safe\getopt;
use function Safe\preg_replace;

$longopts = array("webroot:", "weburl:");
$options = getopt("", $longopts);
$webRoot = $options["webroot"] ?? "/standardebooks.org/web";
$webUrl = $options["weburl"] ?? "https://standardebooks.org";

$contentFiles = explode("\n", trim(shell_exec('find ' . escapeshellarg($webRoot . '/www/ebooks/') . ' -name "content.opf" | sort') ?? ''));
$allEbooks = [];
$newestEbooks = [];

foreach($contentFiles as $path){
	if($path == '')
		continue;

	$ebookWwwFilesystemPath = preg_replace('|/src/.+|ius', '', $path) ?? '';
	$ebook = new Ebook($ebookWwwFilesystemPath);

	$allEbooks[$ebook->ModifiedTimestamp->format('Y-m-d\TH:i:s\Z') . ' ' . $ebook->Identifier] = $ebook;
	$newestEbooks[$ebook->Timestamp->format('Y-m-d\TH:i:s\Z') . ' ' . $ebook->Identifier] = $ebook;
}

krsort($allEbooks);
$allFeed = new OpdsFeed(SITE_URL . '/opds/all', 'All Standard Ebooks', $allEbooks, true);
$allFeed->Save(WEB_ROOT . '/opds/all.xml');

krsort($newestEbooks);
$newestEbooks = array_slice($newestEbooks, 0, 30);
$newestFeed = new OpdsFeed(SITE_URL . '/opds/newest', 'Newest 30 Standard Ebooks', $newestEbooks);
$newestFeed->Save(WEB_ROOT . '/opds/newest.xml');

?>
