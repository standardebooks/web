<?
use function Safe\file_get_contents;
use function Safe\file_put_contents;
use function Safe\gmdate;
use function Safe\rename;
use function Safe\tempnam;

class OpdsAcquisitionFeed extends OpdsFeed{
	public $Ebooks = [];
	public $IsCrawlable;

	public function __construct(string $url, string $title, ?string $parentUrl, array $ebooks, bool $isCrawlable = false){
		parent::__construct($url, $title, $parentUrl);
		$this->Ebooks = $ebooks;
		$this->IsCrawlable = $isCrawlable;
	}

	public function Save(string $path): void{
		$updatedTimestamp = gmdate('Y-m-d\TH:i:s\Z');

		$feed = Template::OpdsAcquisitionFeed(['id' => $this->Id, 'url' => $this->Url, 'title' => $this->Title, 'parentUrl' => $this->ParentUrl, 'updatedTimestamp' => $updatedTimestamp, 'isCrawlable' => $this->IsCrawlable, 'entries' => $this->Ebooks]);

		$this->SaveIfChanged($path, $feed, $updatedTimestamp);
	}
}
