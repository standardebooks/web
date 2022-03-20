<?
require_once('/standardebooks.org/web/lib/Core.php');

use function Safe\file_get_contents;
use function Safe\getopt;
use function Safe\gmdate;
use function Safe\krsort;
use function Safe\preg_replace;
use function Safe\strtotime;

$longopts = ["webroot:", "weburl:"];
$options = getopt("", $longopts);
$webRoot = $options["webroot"] ?? "/standardebooks.org/web";
$webUrl = $options["weburl"] ?? "https://standardebooks.org";

$rssLength = 30;
$contentFiles = explode("\n", trim(shell_exec('find ' . escapeshellarg($webRoot . '/www/ebooks/') . ' -name "content.opf" | sort') ?? ''));

$sortedContentFiles = array();

foreach($contentFiles as $path){
	if($path == '')
		continue;

	$xml = new SimpleXMLElement(str_replace('xmlns=', 'ns=', file_get_contents("$path") ?: ''));
	$xml->registerXPathNamespace('dc', 'http://purl.org/dc/elements/1.1/');

	$temp = $xml->xpath('/package/metadata/dc:date') ?: [];
	$publishedTimestamp = strtotime(array_shift($temp));

	$sortedContentFiles[$publishedTimestamp] = $xml;
}

krsort($sortedContentFiles);

$sortedContentFiles = array_slice($sortedContentFiles, 0, $rssLength);

print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n");
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<title>Standard Ebooks - New Releases</title>
		<link><?= $webUrl ?></link>
		<description>A list of the <?= number_format($rssLength) ?> latest Standard Ebooks ebook releases, most-recently-released first.</description>
		<language>en-US</language>
		<copyright>https://creativecommons.org/publicdomain/zero/1.0/</copyright>
		<lastBuildDate><?= gmdate('D, d M Y H:i:s +0000') ?></lastBuildDate>
		<docs>http://blogs.law.harvard.edu/tech/rss</docs>
		<atom:link href="<?= $webUrl ?>/rss/new-releases" rel="self" type="application/rss+xml"/>
		<image>
			<url><?= $webUrl ?>/images/logo-rss.png</url>
			<title>Standard Ebooks - New Releases</title>
			<description>The Standard Ebooks logo</description>
			<link><?= $webUrl ?></link>
			<height>144</height>
			<width>144</width>
		</image>
		<? foreach($sortedContentFiles as $xml){
			$temp = $xml->xpath('/package/metadata/dc:identifier') ?: [];
			$url = preg_replace('/^url:/ius', '', (string)array_shift($temp) ?? '') ?? '';
			$url = preg_replace('/^https:\/\/standardebooks.org/ius', $webUrl, $url) ?? '';

			$temp = $xml->xpath('/package/metadata/dc:title') ?: [];
			$title = array_shift($temp) ?? '';

			$temp = $xml->xpath('/package/metadata/dc:creator') ?: [];
			$title .= ', by ' . array_shift($temp) ?? '';

			$temp = $xml->xpath('/package/metadata/dc:description') ?: [];
			$description = array_shift($temp) ?? '';

			$temp = $xml->xpath('/package/metadata/dc:date') ?: [];
			$published = gmdate('D, d M Y H:i:s +0000', strtotime(array_shift($temp) ?? '') ?: 0);

			$seSubjects = $xml->xpath('/package/metadata/meta[@property="se:subject"]') ?: [];
		?><item>
			<title><?= $title ?></title>
			<link><?= $url ?></link>
			<description><?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></description>
			<pubDate><?= $published ?></pubDate>
			<guid><?= $url ?></guid>
			<? foreach($seSubjects as $seSubject){ ?>
			<category domain="standardebooks.org"><?= htmlspecialchars($seSubject, ENT_QUOTES, 'UTF-8') ?></category>
			<? } ?>
		</item>
		<? } ?>
	</channel>
</rss>
