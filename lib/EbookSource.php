<?
enum EbookSourceType: string{
	case ProjectGutenberg = 'project_gutenberg';
	case ProjectGutenbergAustralia = 'project_gutenberg_australia';
	case ProjectGutenbergCanada = 'project_gutenberg_canada';
	case InternetArchive = 'internet_archive';
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
