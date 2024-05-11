<?
namespace Exceptions;

class InvalidArtworkTagException extends ValidationException{
	/** @var string $message */
	protected $message = 'Artwork tag is invalid.';

	public function __construct(?string $tagName){
		if($tagName !== null && trim($tagName) != ''){
			$this->message = 'Artwork tag ' . $tagName . ' is invalid.';
		}

		parent::__construct($this->message);
	}
}
