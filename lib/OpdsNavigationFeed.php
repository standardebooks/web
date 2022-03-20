<?
use function Safe\file_get_contents;
use function Safe\file_put_contents;
use function Safe\gmdate;
use function Safe\rename;
use function Safe\tempnam;

class OpdsNavigationFeed extends OpdsFeed{
	public $Entries = [];

	public function __construct(string $url, string $title, ?string $parentUrl, array $entries){
		parent::__construct($url, $title, $parentUrl);
		$this->Entries = $entries;
	}

	public function Save(string $path): void{
		$updatedTimestamp = gmdate('Y-m-d\TH:i:s\Z');

		$feed = Template::OpdsNavigationFeed(['id' => $this->Id, 'url' => $this->Url, 'title' => $this->Title, 'parentUrl' => $this->ParentUrl, 'updatedTimestamp' => $updatedTimestamp, 'entries' => $this->Entries]);

		$this->SaveIfChanged($path, $feed, $updatedTimestamp);
	}
}
