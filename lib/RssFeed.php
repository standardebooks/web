<?
class RssFeed extends Feed{
	public $Description;

	public function __construct(string $url, string $title, string $path, string $description, array $entries){
		parent::__construct($url, $title, $path, $entries);
		$this->Description = $description;
		$this->Stylesheet = '/rss/style';
	}

	protected function GetXmlString(): string{
		if($this->XmlString === null){
			$feed = Template::RssFeed(['url' => $this->Url, 'description' => $this->Description, 'title' => $this->Title, 'entries' => $this->Entries, 'updatedTimestamp' => (new DateTime())->format('r')]);

			$this->XmlString = $this->CleanXmlString($feed);
		}

		return $this->XmlString;
	}
}
