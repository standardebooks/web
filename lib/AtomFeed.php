<?
use function Safe\file_get_contents;
use function Safe\file_put_contents;
use function Safe\preg_replace;
use function Safe\rename;
use function Safe\tempnam;
use function Safe\unlink;

class AtomFeed extends Feed{
	public $Id;

	public function __construct(string $url, string $title, string $path, array $entries){
		parent::__construct($url, $title, $path, $entries);
		$this->Id = 'https://standardebooks.org' . $url;
	}

	private function Sha1Entries(string $xmlString): string{
		try{
			$xml = new SimpleXMLElement(str_replace('xmlns=', 'ns=', $xmlString));
			$xml->registerXPathNamespace('dc', 'http://purl.org/dc/elements/1.1/');
			$xml->registerXPathNamespace('schema', 'http://schema.org/');

			// Remove any <updated> elements, we don't want to compare against those.
			foreach($xml->xpath('/feed/updated') ?: [] as $element){
				unset($element[0]);
			}

			$output = '';
			foreach($xml->xpath('/feed/entry') ?: [] as $entry){
				$output .= $entry->asXml();
			}

			return sha1(preg_replace('/\s/ius', '', $output));
		}
		catch(Exception $ex){
			// Invalid XML
			return '';
		}
	}

	protected function GetXmlString(): string{
		if($this->XmlString === null){
			$feed = Template::AtomFeed(['id' => $this->Id, 'url' => $this->Url, 'title' => $this->Title, 'entries' => $this->Entries]);

			$this->XmlString = $this->CleanXmlString($feed);
		}

		return $this->XmlString;
	}

	protected function HasChanged(string $path): bool{
		return !is_file($path) || ($this->Sha1Entries($this->GetXmlString()) != $this->Sha1Entries(file_get_contents($path)));
	}
}
