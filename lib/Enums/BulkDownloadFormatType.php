<?
namespace Enums;

enum BulkDownloadFormatType: string{
	case Epub = 'epub';
	case Azw3 = 'azw3';
	case Kepub = 'kepub';
	case Xhtml = 'xhtml';
	case AdvancedEpub = 'epub-advanced';

	public function Display(): string{
		return match($this){
			self::AdvancedEpub => 'epub (advanced)',
			default => $this->value,
		};
	}
}
