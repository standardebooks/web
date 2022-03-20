<?
use function Safe\file_get_contents;
use function Safe\file_put_contents;
use function Safe\preg_replace;
use function Safe\rename;
use function Safe\tempnam;
use function Safe\unlink;

class OpdsFeed{
	public $Id;
	public $Url;
	public $Title;
	public $ParentUrl;

	public function __construct(string $url, string $title, ?string $parentUrl){
		$this->Url = $url;
		$this->Id = SITE_URL . $url;
		$this->Title = $title;
		$this->ParentUrl = $parentUrl;
	}

	protected function Sha1Entries(string $xmlString): string{
		try{
			$xml = new SimpleXMLElement(str_replace('xmlns=', 'ns=', $xmlString));
			$xml->registerXPathNamespace('dc', 'http://purl.org/dc/elements/1.1/');
			$xml->registerXPathNamespace('schema', 'http://schema.org/');
			$entries = $xml->xpath('/feed/entry');

			if(!$entries){
				$entries = [];
			}

			$output = '';
			foreach($entries as $entry){
				// Remove any <updated> elements, we don't want to compare against those.
				// This makes it easier to for example generate a new subjects index,
				// while updating it at the same time.
				$elements = $xml->xpath('/feed/entry/updated');

				if(!$elements){
					$elements = [];
				}

				foreach($elements as $element){
					unset($element[0]);
				}

				$output .= $entry->asXml();
			}

			return sha1(preg_replace('/\s/ius', '', $output));
		}
		catch(Exception $ex){
			// Invalid XML
			return '';
		}
	}

	protected function SaveIfChanged(string $path, string $feed, string $updatedTimestamp): void{
		$tempFilename = tempnam('/tmp/', 'se-opds-');
		file_put_contents($tempFilename, $feed);
		exec('se clean ' . escapeshellarg($tempFilename) . ' 2>&1', $output); // Capture the result in case there's an error, otherwise it prints to stdout

		// Did we actually update the feed? If so, write to file and update the index
		if(!is_file($path) || ($this->Sha1Entries($feed) != $this->Sha1Entries(file_get_contents($path)))){
			// Files don't match, save the file and update the parent navigation feed with the last updated timestamp
			$parentFilepath = WEB_ROOT . str_replace(SITE_URL, '', $this->ParentUrl);
			if(!is_file($parentFilepath)){
				$parentFilepath .= '/index.xml';
			}
			$xml = new SimpleXMLElement(str_replace('xmlns=', 'ns=', file_get_contents($parentFilepath)));

			$feedEntries = $xml->xpath('/feed/entry[id="' . $this->Id . '"]/updated');
			if(!$feedEntries){
				$feedEntries = [];
			}

			if(sizeof($feedEntries) > 0){
				$feedEntries[0][0] = $updatedTimestamp;
			}

			$xmlString = $xml->asXml();
			if($xmlString === false){
				$xmlString = '';
			}

			file_put_contents($parentFilepath, str_replace(" ns=", " xmlns=", $xmlString));

			rename($tempFilename, $path);
		}
		else{
			unlink($tempFilename);
		}
	}
}
