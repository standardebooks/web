<?
class RssFeed extends Feed{
	public $Description;

	public function __construct(string $title, string $description, string $url, string $path, array $entries){
		parent::__construct($title, $url, $path, $entries);
		$this->Description = $description;
		$this->Stylesheet = '/feeds/rss/style';
	}

	protected function GetXmlString(): string{
		if($this->XmlString === null){
			$feed = Template::RssFeed(['url' => $this->Url, 'description' => $this->Description, 'title' => $this->Title, 'entries' => $this->Entries, 'updatedTimestamp' => (new DateTime())->format('r')]);

			$this->XmlString = $this->CleanXmlString($feed);
		}

		return $this->XmlString;
	}

	protected function HasChanged(string $path): bool{
		// RSS doesn't have information about when an item was updated,
		// only when it was first published. So, we approximate on whether the feed
		// has changed by looking at the length of the enclosed file.
		// This can sometimes be the same even if the file has changed, but most of the time
		// it also changes.

		if(!is_file($path)){
			return true;
		}

		$currentEntries = [];
		foreach($this->Entries as $entry){
			$obj = new StdClass();
			$obj->Size = (string)filesize(WEB_ROOT . $entry->EpubUrl);
			$obj->Id = preg_replace('/^url:/ius', '', $entry->Identifier);
			$currentEntries[] = $obj;
		}

		$oldEntries = [];
		try{
			$xml = new SimpleXMLElement(str_replace('xmlns=', 'ns=', file_get_contents($path)));
			$xml->registerXPathNamespace('atom', 'http://www.w3.org/2005/Atom');
			foreach($xml->xpath('/rss/channel/item') ?: [] as $entry){
				$obj = new StdClass();
				foreach($entry->xpath('enclosure') ?: [] as $enclosure){
					$obj->Size = (string)$enclosure['length'];
				}
				$obj->Id = (string)$entry->guid;
				$oldEntries[] = $obj;
			}
		}
		catch(Exception $ex){
			// Invalid XML
			return true;
		}

		return $currentEntries != $oldEntries;
	}
}
