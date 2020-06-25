<?
use function Safe\file_get_contents;
use function Safe\file_put_contents;
use function Safe\rename;
use function Safe\tempnam;

class OpdsFeed{
	public $Id;
	public $Url;
	public $Title;
	public $Ebooks = [];
	public $IsCrawlable;

	public function __construct(string $url, string $title, array $ebooks, bool $isCrawlable = false){
		$this->Url = $url;
		$this->Id = $url;
		$this->Title = $title;
		$this->Ebooks = $ebooks;
		$this->IsCrawlable = $isCrawlable;
	}

	private function Sha1Entries(string $xmlString): string{
		$xml = new SimpleXMLElement(str_replace('xmlns=', 'ns=', $xmlString));
		$xml->registerXPathNamespace('dc', 'http://purl.org/dc/elements/1.1/');
		$xml->registerXPathNamespace('schema', 'http://schema.org/');
		$entries = $xml->xpath('/feed/entry') ?? [];

		$output = '';
		foreach($entries as $entry){
			$output .= $entry->asXml();
		}

		return sha1(preg_replace('/\s/ius', '', $output));
	}

	public function Save(string $filename): void{
		$updatedTimestamp = gmdate('Y-m-d\TH:i:s\Z');

		$feed = Template::OpdsFeed(['id' => $this->Url, 'url' => $this->Url, 'title' => $this->Title, 'updatedTimestamp' => $updatedTimestamp, 'isCrawlable' => $this->IsCrawlable, 'entries' => $this->Ebooks]);

		$tempFilename = tempnam('/tmp/', 'se-opds-');
		file_put_contents($tempFilename, $feed);
		exec('se clean ' . escapeshellarg($tempFilename));

		// Did we actually update the feed? If so, write to file and update the index
		if(!is_file($filename)){
			// File doesn't exist, write it out
			rename($tempFilename, $filename);
		}
		elseif($this->Sha1Entries($feed) != $this->Sha1Entries(file_get_contents($filename))){
			// Files don't match, save the file and update the index feed with the last updated timestamp
			$xml = new SimpleXMLElement(str_replace('xmlns=', 'ns=', file_get_contents(WEB_ROOT . '/opds/index.xml')));
			$xml->registerXPathNamespace('dc', 'http://purl.org/dc/elements/1.1/');
			$xml->registerXPathNamespace('schema', 'http://schema.org/');

			$feedEntry = ($xml->xpath('/feed/entry[id="' . $this->Id . '"]/updated') ?? [])[0];
			$feedEntry[0] = $updatedTimestamp;
			file_put_contents(WEB_ROOT . '/opds/index.xml', str_replace(" ns=", " xmlns=", $xml->asXml() ?? ''));

			rename($tempFilename, $filename);
		}
	}
}
