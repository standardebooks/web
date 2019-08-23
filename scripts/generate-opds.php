<?
$longopts = array("webroot:", "weburl:");
$options = getopt("", $longopts);
$webRoot = $options["webroot"] ?? "/standardebooks.org/web";
$webUrl = $options["weburl"] ?? "https://standardebooks.org";

$contentFiles = explode("\n", trim(shell_exec('find ' . escapeshellarg($webRoot . '/www/ebooks/') . ' -name "content.opf" | sort') ?? ''));

print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n");
?>
<feed xmlns="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:schema="http://schema.org/">
	<id><?= $webUrl ?>/opds/all</id>
	<link href="<?= $webUrl ?>/opds/all" rel="self" type="application/atom+xml;profile=opds-catalog;kind=acquisition"/>
	<link href="<?= $webUrl ?>/opds/" rel="start" type="application/atom+xml;profile=opds-catalog;kind=navigation"/>
	<title>All Standard Ebooks</title>
	<subtitle>Free and liberated ebooks, carefully produced for the true book lover.</subtitle>
	<icon><?= $webUrl ?>/images/logo.png</icon>
	<updated><?= gmdate('Y-m-d\TH:i:s\Z') ?></updated>
	<author>
		<name>Standard Ebooks</name>
		<uri><?= $webUrl ?></uri>
	</author>
	<? foreach($contentFiles as $path){
	if($path == '')
		continue;

	$xml = new SimpleXMLElement(str_replace('xmlns=', 'ns=', file_get_contents("$path") ?: ''));
	$xml->registerXPathNamespace('dc', 'http://purl.org/dc/elements/1.1/');

	$authors = array();
	$temp = $xml->xpath('/package/metadata/dc:identifier') ?: [];
	$url = preg_replace('/^url:/ius', '', (string)array_shift($temp)) ?? '';
	$url = preg_replace('/^https:\/\/standardebooks.org/ius', $webUrl, $url) ?? '';
	$relativeUrl = preg_replace('/^' . preg_quote($webUrl, '/') . '/ius', '', $url) ?? '';

	$temp = $xml->xpath('/package/metadata/dc:title') ?: [];
	$title = array_shift($temp);

	$temp = $xml->xpath('/package/metadata/meta[@property="se:long-description"]') ?: [];
	$longDescription = array_shift($temp);

	$authors = $xml->xpath('/package/metadata/dc:creator') ?: [];

	$temp = $xml->xpath('/package/metadata/dc:date') ?: [];
	$published = array_shift($temp);

	$temp = $xml->xpath('/package/metadata/dc:language') ?: [];
	$language = array_shift($temp);

	$temp = $xml->xpath('/package/metadata/meta[@property="dcterms:modified"]') ?: [];
	$modified = array_shift($temp);

	$temp = $xml->xpath('/package/metadata/dc:description') ?: [];
	$description = array_shift($temp);

	$subjects = $xml->xpath('/package/metadata/dc:subject') ?: [];

	$sources = $xml->xpath('/package/metadata/dc:source') ?: [];

	$filesystemPath = preg_replace('/\/src\/epub\/content.opf$/ius', '', $path) ?? '';
	$temp = glob($filesystemPath . '/dist/*.epub');
	$epubFilename = preg_replace('/(\|\.epub)/ius', '', preg_replace('/.+\//ius', '', array_shift($temp) ?? '') ?? '') ?? '';
	$temp = glob($filesystemPath . '/dist/*.azw3');
	$kindleFilename = preg_replace('/.+\//ius', '', array_shift($temp) ?? '') ?? '';

	?>
	<entry>
		<id><?= $url ?></id>
		<title><?= $title ?></title>
		<? foreach($authors as $author){
			$id = '';
			if($author->attributes() !== null){
				$id = $author->attributes()->id;
			}
			$temp = $xml->xpath('/package/metadata/meta[@property="se:url.encyclopedia.wikipedia"][@refines="#' . $id . '"]') ?: [];
			$wikiUrl = array_shift($temp);
			$temp = $xml->xpath('/package/metadata/meta[@property="se:name.person.full-name"][@refines="#' . $id . '"]') ?: [];
			$fullName = array_shift($temp);
			$temp = $xml->xpath('/package/metadata/meta[@property="se:url.authority.nacoaf"][@refines="#' . $id . '"]') ?: [];
			$nacoafLink = array_shift($temp);
		?>
		<author>
			<name><?= $author ?></name>
			<? if($wikiUrl !== null){ ?><uri><?= $wikiUrl ?></uri><? } ?>
			<? if($fullName !== null){ ?><schema:alternateName><?= $fullName ?></schema:alternateName><? } ?>
			<? if($nacoafLink !== null){ ?><schema:sameAs><?= $nacoafLink ?></schema:sameAs><? } ?>
		</author>
		<? } ?>
		<published><?= $published ?></published>
		<updated><?= $modified ?></updated>
		<dc:language><?= $language ?></dc:language>
		<dc:publisher>Standard Ebooks</dc:publisher>
		<? foreach($sources as $source){ ?>
		<dc:source><?= $source ?></dc:source>
		<? } ?>
		<rights>Public domain in the United States; original content released to the public domain via the Creative Commons CC0 1.0 Universal Public Domain Dedication</rights>
		<summary type="text"><?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></summary>
		<content type="text/html"><?= $longDescription ?></content>
		<? foreach($subjects as $subject){ ?>
		<category scheme="http://purl.org/dc/terms/LCSH" term="<?= htmlspecialchars($subject, ENT_QUOTES, 'UTF-8') ?>"/>
		<? } ?>
		<link href="<?= $relativeUrl ?>/dist/cover.jpg" rel="http://opds-spec.org/image" type="image/jpeg"/>
		<link href="<?= $relativeUrl ?>/dist/cover-thumbnail.jpg" rel="http://opds-spec.org/image/thumbnail" type="image/jpeg"/>
		<link href="<?= $relativeUrl ?>/src/epub/images/cover.svg" rel="http://opds-spec.org/image" type="image/svg+xml"/>
		<link href="<?= $relativeUrl ?>/dist/<?= $epubFilename ?>" rel="http://opds-spec.org/acquisition/open-access" type="application/epub+zip"/>
		<link href="<?= $relativeUrl ?>/dist/<?= $epubFilename ?>3" rel="http://opds-spec.org/acquisition/open-access" type="application/epub+zip"/>
		<link href="<?= $relativeUrl ?>/dist/<?= preg_replace('/\.epub$/ius', '.kepub.epub', $epubFilename) ?>" rel="http://opds-spec.org/acquisition/open-access" type="application/kepub+zip"/>
		<link href="<?= $relativeUrl ?>/dist/<?= $kindleFilename ?>" rel="http://opds-spec.org/acquisition/open-access" type="application/x-mobipocket-ebook"/>
	</entry>
	<? } ?>
</feed>
