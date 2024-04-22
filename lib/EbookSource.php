<?
enum EbookSourceType: string{
	case ProjectGutenberg = 'pg';
	case ProjectGutenbergAustralia = 'pg_australia';
	case ProjectGutenbergCanada = 'pg_canada';
	case InternetArchive = 'ia';
	case HathiTrust = 'hathi_trust';
	case Wikisource = 'wikisource';
	case GoogleBooks = 'google_books';
	case FadedPage = 'faded_page';
	case Other = 'other';
}

class EbookSource{
	public EbookSourceType $Type;
	public string $Url;

	public function __construct(EbookSourceType $type, string $url){
		$this->Type = $type;
		$this->Url = $url;
	}
}
