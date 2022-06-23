<?
use function Safe\file_get_contents;
use function Safe\file_put_contents;
use function Safe\preg_replace;
use function Safe\rename;
use function Safe\tempnam;
use function Safe\unlink;

class AtomFeed extends Feed{
	public $Id;
	public $Updated = null;
	public $Subtitle = null;

	public function __construct(string $title, string $subtitle, string $url, string $path, array $entries){
		parent::__construct($title, $url, $path, $entries);
		$this->Subtitle = $subtitle;
		$this->Id = $url;
		$this->Stylesheet = '/atom/style';
	}

	protected function GetXmlString(): string{
		if($this->XmlString === null){
			$feed = Template::AtomFeed(['id' => $this->Id, 'url' => $this->Url, 'title' => $this->Title, 'subtitle' => $this->Subtitle, 'updatedTimestamp' => $this->Updated, 'entries' => $this->Entries]);

			$this->XmlString = $this->CleanXmlString($feed);
		}

		return $this->XmlString;
	}

	protected function HasChanged(string $path): bool{
		if(!is_file($path)){
			return true;
		}

		$currentEntries = [];
		foreach($this->Entries as $entry){
			$obj = new StdClass();
			if(is_a($entry, 'Ebook')){
				$obj->Updated = $entry->ModifiedTimestamp->format('Y-m-d\TH:i:s\Z');
				$obj->Id = SITE_URL . $entry->Url;
			}
			else{
				$obj->Updated = $entry->Updated !== null ? $entry->Updated->format('Y-m-d\TH:i:s\Z') : '';
				$obj->Id = $entry->Id;
			}
			$currentEntries[] = $obj;
		}

		$oldEntries = [];
		try{
			$xml = new SimpleXMLElement(str_replace('xmlns=', 'ns=', file_get_contents($path)));

			foreach($xml->xpath('/feed/entry') ?: [] as $entry){
				$obj = new StdClass();
				$obj->Updated = $entry->updated;
				$obj->Id = $entry->id;
				$oldEntries[] = $obj;
			}
		}
		catch(Exception $ex){
			// Invalid XML
			return true;
		}

		return $currentEntries != $oldEntries;
	}

	public function Save(): void{
		parent::Save();
	}
}
