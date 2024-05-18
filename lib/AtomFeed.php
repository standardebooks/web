<?
use Safe\DateTimeImmutable;
use function Safe\file_get_contents;

class AtomFeed extends Feed{
	public string $Id;
	public ?DateTimeImmutable $Updated = null;
	public ?string $Subtitle = null;

	/**
	 * @param string $title
	 * @param string $subtitle
	 * @param string $url
	 * @param string $path
	 * @param array<Ebook|OpdsNavigationEntry> $entries
	 */
	public function __construct(string $title, string $subtitle, string $url, string $path, array $entries){
		parent::__construct($title, $url, $path, $entries);
		$this->Subtitle = $subtitle;
		$this->Id = $url;
		$this->Stylesheet = SITE_URL . '/feeds/atom/style';
	}

	// *******
	// METHODS
	// *******

	protected function GetXmlString(): string{
		if($this->XmlString === null){
			$feed = Template::AtomFeed(['id' => $this->Id, 'url' => $this->Url, 'title' => $this->Title, 'subtitle' => $this->Subtitle, 'updated' => $this->Updated, 'entries' => $this->Entries]);

			$this->XmlString = $this->CleanXmlString($feed);
		}

		return $this->XmlString;
	}

	public function SaveIfChanged(): bool{
		// Did we actually update the feed? If so, write to file and update the index
		if($this->HasChanged($this->Path)){
			// Files don't match, save the file
			/** @throws void */
			$this->Updated = new DateTimeImmutable();
			$this->Save();
			return true;
		}

		return false;
	}

	protected function HasChanged(string $path): bool{
		if(!is_file($path)){
			return true;
		}

		$currentEntries = [];
		foreach($this->Entries as $entry){
			$obj = new StdClass();
			if($entry instanceof Ebook){
				$obj->Updated = $entry->EbookUpdated->format('Y-m-d\TH:i:s\Z');
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
		catch(Exception){
			// Invalid XML
			return true;
		}

		return $currentEntries != $oldEntries;
	}

	public function Save(): void{
		parent::Save();
	}
}
