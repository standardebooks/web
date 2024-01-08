<?
class EbookSource{
	public int $Type;
	public string $Url;

	public function __construct(int $type, string $url){
		$this->Type = $type;
		$this->Url = $url;
	}
}
