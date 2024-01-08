<?
class OpdsNavigationEntry{
	public string $Id;
	public string $Url;
	public string $Rel;
	public string $Type;
	public ?DateTime $Updated;
	public string $Description;
	public string $Title;
	public string $SortTitle;

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
