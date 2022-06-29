<?
use Safe\DateTime;
use function Safe\file_put_contents;

class OpdsFeed extends AtomFeed{
	public $Parent = null; // OpdsNavigationFeed class

	public function __construct(string $title, string $subtitle, string $url, string $path, array $entries, ?OpdsNavigationFeed $parent){
		parent::__construct($title, $subtitle, $url, $path, $entries);
		$this->Parent = $parent;
		$this->Stylesheet = '/feeds/opds/style';
	}

	protected function SaveUpdated(string $entryId, DateTime $updated): void{
		// Only save the updated timestamp for the given entry ID in this file
		foreach($this->Entries as $entry){
			if(is_a($entry, 'OpdsNavigationEntry')){
				if($entry->Id == SITE_URL . $entryId){
					$entry->Updated = $updated;
				}
			}
		}

		$this->Updated = $updated;

		$this->XmlString = null;
		file_put_contents($this->Path, $this->GetXmlString());

		// Do we have any parents of our own to update?
		if($this->Parent !== null){
			$this->Parent->SaveUpdated($this->Id, $updated);
		}
	}

	public function SaveIfChanged(): void{
		// Did we actually update the feed? If so, write to file and update the index
		if($this->HasChanged($this->Path)){
			// Files don't match, save the file and update the parent navigation feed with the last updated timestamp

			$this->Updated = new DateTime();

			if($this->Parent !== null){
				$this->Parent->SaveUpdated($this->Id, $this->Updated);
			}

			// Save our own file
			$this->Save();
		}
	}
}
