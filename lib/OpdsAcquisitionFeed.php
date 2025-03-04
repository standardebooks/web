<?
/**
 * @property array<Ebook> $Entries
 */
class OpdsAcquisitionFeed extends OpdsFeed{
	public bool $IsCrawlable;

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
}
