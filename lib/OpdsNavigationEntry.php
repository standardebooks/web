<?
class OpdsNavigationEntry{
	public $Id;
	public $Url;
	public $Rel;
	public $Type;
	public $Updated;
	public $Description;
	public $Title;

	public function __construct(string $url, string $rel, string $type, ?DateTime $updated, string $title, string $description){
		$this->Id = SITE_URL . $url;
		$this->Url = $url;
		$this->Rel = $rel;
		$this->Type = $type;
		$this->Updated = $updated;
		$this->Title = $title;
		$this->Description = $description;
	}
}
