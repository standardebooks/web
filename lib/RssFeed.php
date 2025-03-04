<?
use function Safe\file_get_contents;
use function Safe\filesize;
use function Safe\preg_replace;

/**
 * @property array<Ebook> $Entries
 */
class RssFeed extends Feed{
	public string $Description;

	/**
	 * @param array<Ebook> $entries
	 */
	public function __construct(string $title, string $description, string $url, string $path, array $entries){
		parent::__construct($title, $url, $path, $entries);
		$this->Description = $description;
		$this->Stylesheet = SITE_URL . '/feeds/rss/style';
	}


	// *******
	// METHODS
	// *******

	protected function GetXmlString(): string{
		return $this->_XmlString ??= $this->CleanXmlString(Template::RssFeed(url: $this->Url, description: $this->Description, title: $this->Title, entries: $this->Entries, updated: NOW));
	}

	public function SaveIfChanged(): bool{
		// Did we actually update the feed? If so, write to file and update the index.
		if($this->HasChanged($this->Path)){
			// Files don't match, save the file.
			$this->Save();
			return true;
		}

		return false;
	}

	protected function HasChanged(string $path): bool{
		// RSS doesn't have information about when an item was updated, only when it was first published. So, we approximate on whether the feed has changed by looking at the length of the enclosed file. This can sometimes be the same even if the file has changed, but most of the time it also changes.

		if(!is_file($path)){
			return true;
		}

		$currentEntries = [];
		foreach($this->Entries as $entry){
			/** @var Ebook $entry */
			$obj = new stdClass();
			$obj->Size = (string)filesize(WEB_ROOT . $entry->EpubUrl);
			$obj->Id = preg_replace('/^url:/ius', '', $entry->Identifier);
			$currentEntries[] = $obj;
		}

		$oldEntries = [];
		try{
			$xml = new SimpleXMLElement(str_replace('xmlns=', 'ns=', file_get_contents($path)));
			$xml->registerXPathNamespace('atom', 'http://www.w3.org/2005/Atom');
			foreach($xml->xpath('/rss/channel/item') ?: [] as $entry){
				$obj = new stdClass();
				foreach($entry->xpath('enclosure') ?: [] as $enclosure){
					$obj->Size = (string)$enclosure['length'];
				}
				$obj->Id = (string)$entry->guid;
				$oldEntries[] = $obj;
			}
		}
		catch(Exception){
			// Invalid XML.
			return true;
		}

		return $currentEntries != $oldEntries;
	}
}
