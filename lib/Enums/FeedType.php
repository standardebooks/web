<?
namespace Enums;

enum FeedType: string{
	case Atom = 'atom';
	case Opds = 'opds';
	case Rss = 'rss';

	public function GetDisplayName(): string{
		return match($this){
			self::Atom => 'Atom 1.0',
			self::Opds => 'OPDS 1.2',
			self::Rss => 'RSS 2.0',
		};
	}
}
