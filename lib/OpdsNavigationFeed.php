<?
use Safe\DateTime;

use function Safe\file_get_contents;

class OpdsNavigationFeed extends OpdsFeed{
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
						if($entry->Id == $existingEntry->id){
							$entry->Updated = new DateTime($existingEntry->updated);
						}
					}
				}
			}
			catch(Exception $ex){
				// XML parsing failure
			}
		}
	}

	protected function GetXmlString(): string{
		if($this->XmlString === null){
			$this->XmlString = $this->CleanXmlString(Template::OpdsNavigationFeed(['id' => $this->Id, 'url' => $this->Url, 'title' => $this->Title, 'parentUrl' => $this->Parent ? $this->Parent->Url : null, 'updatedTimestamp' => $this->Updated, 'subtitle' => $this->Subtitle, 'entries' => $this->Entries]));
		}

		return $this->XmlString;
	}
}
