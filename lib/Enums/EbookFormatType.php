<?
namespace Enums;

enum EbookFormatType: string{
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

	/**
	 * @throws \Exceptions\InvalidEbookFormatException
	 */
	public static function FromFilename(string $filename): self{
		if(str_ends_with($filename, '.azw3')){
			return self::Azw3;
		}

		if(str_ends_with($filename, '.kepub.epub')){
			return self::Kepub;
		}

		if(str_ends_with($filename, '_advanced.epub')){
			return self::AdvancedEpub;
		}

		if(str_ends_with($filename, '.epub')){
			return self::Epub;
		}

		throw new \Exceptions\InvalidEbookFormatException();
	}
}
