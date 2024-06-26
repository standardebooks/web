<?
namespace Exceptions;

class InvalidArtworkTagTypeException extends AppException{
	/** @var string $message */
	protected $message = 'Type should be `artwork`.';

	public function __construct(?string $tagType){
		if($tagType !== null && trim($tagType) != ''){
			$this->message .= ' Type provided: ' . $tagType;
		}

		parent::__construct($this->message);
	}
}
