<?
class OpdsAcquisitionFeed extends OpdsFeed{
	public bool $IsCrawlable;

	public function __construct(string $title, string $subtitle, string $url, string $path, array $entries, ?OpdsNavigationFeed $parent, bool $isCrawlable = false){
		parent::__construct($title, $subtitle, $url, $path, $entries, $parent);
		$this->IsCrawlable = $isCrawlable;
	}


	// *******
	// METHODS
	// *******

	protected function GetXmlString(): string{
		if(!isset($this->_XmlString)){
			$this->_XmlString = $this->CleanXmlString(Template::OpdsAcquisitionFeed(['id' => $this->Id, 'url' => $this->Url, 'title' => $this->Title, 'parentUrl' => $this->Parent ? $this->Parent->Url : null, 'updated' => $this->Updated, 'isCrawlable' => $this->IsCrawlable, 'subtitle' => $this->Subtitle, 'entries' => $this->Entries]));
		}

		return $this->_XmlString;
	}
}
