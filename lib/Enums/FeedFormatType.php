<?
namespace Enums;

enum FeedFormatType: string{
	// Maintain this order to display OPDS first in the various feed listings.
	case Opds = 'opds';
	case Atom = 'atom';

	public function GetDisplayName(): string{
		return match($this){
			self::Atom => 'RSS/Atom',
			self::Opds => 'OPDS',
		};
	}
}
