<?
class EbookSource{
	public EbookSourceType $Type;
	public string $Url;

	public static function FromFile(EbookSourceType $type, string $url): EbookSource{
		$instance = new EbookSource();
		$instance->Type = $type;
		$instance->Url = $url;
		return $instance;
	}
}
