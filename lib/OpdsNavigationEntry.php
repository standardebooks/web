<?
class OpdsNavigationEntry{
	public $Id;
	public $Url;
	public $Rel;
	public $Type;
	public $Updated;
	public $Description;
	public $Title;
	public $SortTitle;

	public function __construct(string $title, string $description, string $url, ?DateTime $updated, string $rel, string $type){
		$this->Id = SITE_URL . $url;
		$this->Url = $url;
		$this->Rel = $rel;
		$this->Type = $type;
		$this->Updated = $updated;
		$this->Title = $title;
		$this->Description = $description;
	}
}
