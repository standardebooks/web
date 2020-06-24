<?
$longopts = array("webroot:", "weburl:");
$options = getopt("", $longopts);
$webRoot = $options["webroot"] ?? "/standardebooks.org/web";
$webUrl = $options["weburl"] ?? "https://standardebooks.org";

require_once($webRoot . '/lib/Core.php');

$updatedTimestamp = gmdate('Y-m-d\TH:i:s\Z');

$contentFiles = explode("\n", trim(shell_exec('find ' . escapeshellarg($webRoot . '/www/ebooks/') . ' -name "content.opf" | sort') ?? ''));
$sortedContentFiles = [];

$allFeedEbooks = '';

foreach($contentFiles as $path){
	if($path == '')
		continue;

	$ebookWwwFilesystemPath = preg_replace('|/src/.+|ius', '', $path) ?? '';
	$ebook = new Ebook($ebookWwwFilesystemPath);

	$sortedContentFiles[$ebook->ModifiedTimestamp->format('Y-m-dTH:i:sZ') . ' ' . $ebook->Identifier] = $ebook;
}

krsort($sortedContentFiles);

$url = SITE_URL . '/opds/all';

$feed = Template::OpdsFeed(['id' => $url, 'url' => $url, 'title' => 'All Standard Ebooks', 'updatedTimestamp' => $updatedTimestamp, 'isCrawlable' => true, 'entries' => $sortedContentFiles]);

$tempFilename = tempnam('/tmp/', 'se-opds-');

file_put_contents($tempFilename, $feed);
exec('se clean ' . escapeshellarg($tempFilename));

// If the feed has changed compared to the version currently on disk, copy our new version over
// and update the updated timestamp in the master opds index.
try{
	if(filesize($webRoot . '/www/opds/all.xml') !== filesize($tempFilename)){
		$oldFeed = file_get_contents($webRoot . '/www/opds/all.xml');
		$newFeed = file_get_contents($tempFilename);
		if($oldFeed != $newFeed){
			file_put_contents($webRoot . '/www/opds/all.xml', $newFeed);

			// Update the index feed with the last updated timestamp
			$xml = new SimpleXMLElement(str_replace('xmlns=', 'ns=', file_get_contents($webRoot . '/www/opds/index.xml')));
			$xml->registerXPathNamespace('dc', 'http://purl.org/dc/elements/1.1/');
			$xml->registerXPathNamespace('schema', 'http://schema.org/');

			$allUpdated = $xml->xpath('/feed/entry[id="https://standardebooks.org/opds/all"]/updated')[0];
			$allUpdated[0] = $updatedTimestamp;
			file_put_contents($webRoot . '/www/opds/index.xml', str_replace(" ns=", " xmlns=", $xml->asXml()));
			exec('se clean ' . escapeshellarg($webRoot) . '/www/opds/index.xml');
		}
	}
}
catch(Exception $ex){
	rename($tempFilename, $webRoot . '/www/opds/all.xml');
}

unlink($tempFilename);

?>
