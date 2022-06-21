<?
use Safe\DateTime;

class OpdsAcquisitionFeed extends OpdsFeed{
	public $IsCrawlable;

	public function __construct(string $url, string $title, string $path, array $entries, ?OpdsNavigationFeed $parent, bool $isCrawlable = false){
		parent::__construct($url, $title, $path, $entries, $parent);
		$this->IsCrawlable = $isCrawlable;
	}

	protected function GetXmlString(): string{
		if($this->XmlString === null){
			$this->XmlString = $this->CleanXmlString(Template::OpdsAcquisitionFeed(['id' => $this->Id, 'url' => $this->Url, 'title' => $this->Title, 'parentUrl' => $this->Parent ? $this->Parent->Url : null, 'updatedTimestamp' => $this->Updated, 'isCrawlable' => $this->IsCrawlable, 'entries' => $this->Entries]));
		}

		return $this->XmlString;
	}

	public function Save(): void{
		$this->Updated = new DateTime();
		$this->SaveIfChanged();
	}
}
