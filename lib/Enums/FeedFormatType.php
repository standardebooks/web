<?
namespace Enums;

enum FeedFormatType: string{
	// Maintain this order to display OPDS first in the various feed listings.
	case Opds = 'opds';
	case Atom = 'atom';
	/** Deprecated but kept for backwards compatibility. */
	case Rss = 'rss';

	public function GetDisplayName(): string{
		return match($this){
			self::Atom => 'RSS/Atom',
			self::Opds => 'OPDS',
			self::Rss => 'RSS 2.0',
		};
	}
}
