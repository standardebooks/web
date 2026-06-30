<?
class OpdsAcquisitionFeed extends OpdsFeed{
	public bool $IsCrawlable;
	/** @var array<Ebook> */
	public $Entries = [];

	/**
	 * @param array<Ebook> $entries
	 */
	public function __construct(string $title, string $subtitle, string $url, string $path, array $entries, ?OpdsNavigationFeed $parent, bool $isCrawlable = false){
		parent::__construct($title, $subtitle, $url, $path, $entries, $parent);
		$this->IsCrawlable = $isCrawlable;
	}


	// *******
	// METHODS
	// *******

	protected function GetXmlString(): string{
		return $this->_XmlString ??= $this->CleanXmlString(Template::OpdsAcquisitionFeed(id: $this->Id, url: $this->Url, title: $this->Title, parentUrl: $this->Parent->Url ?? '', updated: $this->Updated, isCrawlable: $this->IsCrawlable, subtitle: $this->Subtitle, entries: $this->Entries));
	}

	/**
	 * Return the OPDS 2.0 acquisition feed object.
	 *
	 * @return array<string, mixed>
	 */
	protected function GetJsonObject(): array{
		$output = parent::GetJsonObject();
		$output['publications'] = [];

		foreach($this->Entries as $entry){
			$output['publications'][] = $this->GenerateOpds2Publication($entry);
		}

		return $output;
	}
}
