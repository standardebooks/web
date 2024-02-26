<?

enum EbookFormat: string{
	case Epub = 'epub';
	case Azw3 = 'azw3';
	case Kepub = 'kepub';
	case AdvancedEpub = 'advanced-epub';

	public function GetMimeType(): string{
		return match($this){
			self::Azw3 => 'application/x-mobi8-ebook',
			default => 'application/epub+zip'
		};
	}
}
