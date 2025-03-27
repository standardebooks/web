<?
use Safe\DateTimeImmutable;
use function Safe\file_put_contents;

/**
 * @property array<Ebook|OpdsNavigationEntry> $Entries
 */
abstract class OpdsFeed extends AtomFeed{
	public ?OpdsNavigationFeed $Parent = null;

	/**
	 * @param string $title
	 * @param string $subtitle
	 * @param string $url
	 * @param string $path
	 * @param array<Ebook|OpdsNavigationEntry> $entries
	 * @param OpdsNavigationFeed $parent
	 */
	public function __construct(string $title, string $subtitle, string $url, string $path, array $entries, ?OpdsNavigationFeed $parent){
		parent::__construct($title, $subtitle, $url, $path, $entries);
		$this->Parent = $parent;
		$this->Stylesheet = SITE_URL . '/feeds/opds/style';
	}


	// *******
	// METHODS
	// *******

	protected function SaveUpdated(string $entryId, DateTimeImmutable $updated): void{
		// Only save the updated timestamp for the given entry ID in this file
		foreach($this->Entries as $entry){
			if($entry instanceof OpdsNavigationEntry){
				if($entry->Id == SITE_URL . $entryId){
					$entry->Updated = $updated;
				}
			}
		}

		$this->Updated = $updated;

		/** @phpstan-ignore-next-line */
		unset($this->_XmlString);
		file_put_contents($this->Path, $this->GetXmlString());

		// Do we have any parents of our own to update?
		if($this->Parent !== null){
			$this->Parent->SaveUpdated($this->Id, $updated);
		}
	}

	public function SaveIfChanged(): bool{
		// Did we actually update the feed? If so, write to file and update the index
		if($this->HasChanged($this->Path)){
			// Files don't match, save the file and update the parent navigation feed with the last updated timestamp

			$this->Updated = NOW;

			if($this->Parent !== null){
				$this->Parent->SaveUpdated($this->Id, $this->Updated);
			}

			// Save our own file
			$this->Save();
			return true;
		}

		return false;
	}
}
