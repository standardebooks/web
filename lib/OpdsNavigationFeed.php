<?
use Safe\DateTimeImmutable;
use function Safe\file_get_contents;

/**
 * @property array<OpdsNavigationEntry> $Entries
 */
class OpdsNavigationFeed extends OpdsFeed{
	/**
	 * @param array<OpdsNavigationEntry> $entries
	 */
	public function __construct(string $title, string $subtitle, string $url, string $path, array $entries, ?OpdsNavigationFeed $parent){
		parent::__construct($title, $subtitle, $url, $path, $entries, $parent);

		// If the file already exists, try to fill in the existing updated timestamps from the file.
		// That way, if the file has changed, we only update the changed entry,
		// and not every single entry. This is only relevant to navigation feeds,
		// because their *entries* along with their root updated timestamp change if their entries have an update.
		// For acquisition feeds, only the root updated timestamp changes, so this is not a concern.
		if(file_exists($this->Path)){
			try{
				$xml = new SimpleXMLElement(str_replace('xmlns=', 'ns=', file_get_contents($this->Path)));
				foreach($xml->xpath('//entry') ?: [] as $existingEntry){
					foreach($this->Entries as $entry){
						/** @var OpdsNavigationEntry $entry */
						if($entry->Id == $existingEntry->id){
							$entry->Updated = new DateTimeImmutable($existingEntry->updated);
						}
					}
				}
			}
			catch(Exception){
				// XML parsing failure
			}
		}
	}


	// *******
	// METHODS
	// *******

	protected function GetXmlString(): string{
		return $this->_XmlString ??= $this->CleanXmlString(Template::OpdsNavigationFeed(id: $this->Id, url: $this->Url, title: $this->Title, parentUrl: $this->Parent->Url ?? '', updated: $this->Updated, subtitle: $this->Subtitle, entries: $this->Entries));
	}
}
