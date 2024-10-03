<?
namespace Exceptions;

use \TagType;

class InvalidArtworkTagTypeException extends AppException{
	/** @var string $message */
	protected $message = 'Type should be `TagType::Artwork`.';

	public function __construct(?TagType $tagType){
		if($tagType !== null){
			$this->message .= ' Type provided: ' . $tagType->value;
		}

		parent::__construct($this->message);
	}
}
