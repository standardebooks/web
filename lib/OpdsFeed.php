<?
use function Safe\file_put_contents;

class OpdsFeed extends AtomFeed{
	public $Updated = null;
	public $Parent = null; // OpdsNavigationFeed class

	public function __construct(string $url, string $title, string $path, array $entries, ?OpdsNavigationFeed $parent){
		parent::__construct($url, $title, $path, $entries);
		$this->Parent = $parent;
		$this->Stylesheet = '/opds/style';
	}

	protected function SaveUpdatedTimestamp(string $entryId, DateTime $updatedTimestamp): void{
		// Only save the updated timestamp for the given entry ID in this file

		foreach($this->Entries as $entry){
			if($entry->Id == $entryId){
				$entry->Updated = $updatedTimestamp;
			}
		}

		$this->XmlString = null;
		file_put_contents($this->Path, $this->GetXmlString());

		// Do we have any parents of our own to update?
		if($this->Parent !== null){
			$this->Parent->SaveUpdatedTimestamp($this->Id, $updatedTimestamp);
		}
	}

	protected function SaveIfChanged(): void{
		// Did we actually update the feed? If so, write to file and update the index
		if($this->HasChanged($this->Path)){
			// Files don't match, save the file and update the parent navigation feed with the last updated timestamp

			if($this->Parent !== null){
				$this->Parent->SaveUpdatedTimestamp($this->Id, $this->Updated);
			}

			// Save our own file
			parent::Save();
		}
	}
}
