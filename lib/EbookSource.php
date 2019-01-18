<?
class EbookSource{
	public $Type;
	public $Url;

	public function __construct(int $type, string $url){
		$this->Type = $type;
		$this->Url = $url;
	}
}
?>
