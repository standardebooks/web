<?
class EbookSource{
	public EbookSourceType $Type;
	public string $Url;

	public function __construct(EbookSourceType $type, string $url){
		$this->Type = $type;
		$this->Url = $url;
	}
}
