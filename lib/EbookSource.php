<?
enum EbookSourceType{
	case ProjectGutenberg;
	case ProjectGutenbergAustralia;
	case ProjectGutenbergCanada;
	case InternetArchive;
	case HathiTrust;
	case Wikisource;
	case GoogleBooks;
	case FadedPage;
	case Other;
}

class EbookSource{
	public EbookSourceType $Type;
	public string $Url;

	public function __construct(EbookSourceType $type, string $url){
		$this->Type = $type;
		$this->Url = $url;
	}
}
