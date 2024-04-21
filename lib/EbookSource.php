<?
enum EbookSourceType: int{
	case ProjectGutenberg = 1;
	case ProjectGutenbergAustralia = 2;
	case ProjectGutenbergCanada = 3;
	case InternetArchive = 4;
	case HathiTrust = 5;
	case Wikisource = 6;
	case GoogleBooks = 7;
	case FadedPage = 8;
	case Other = 9;
}

class EbookSource{
	public EbookSourceType $Type;
	public string $Url;

	public function __construct(EbookSourceType $type, string $url){
		$this->Type = $type;
		$this->Url = $url;
	}
}
