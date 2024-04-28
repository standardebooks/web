<?
namespace Exceptions;

class InvalidArtworkTagException extends ValidationException{
	protected $message = 'Artwork tag is invalid.';

	public function __construct(?string $tagName){
		if($tagName !== null && trim($tagName) != ''){
			$this->message = 'Artwork tag ' . $tagName . ' is invalid.';
		}
	}
}
