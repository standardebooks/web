<?
namespace Exceptions;

class InvalidEbookTagTypeException extends AppException{
	/** @var string $message */
	protected $message = 'Type should be `ebook`.';

	public function __construct(?string $tagType){
		if($tagType !== null && trim($tagType) != ''){
			$this->message .= ' Type provided: ' . $tagType;
		}

		parent::__construct($this->message);
	}
}
