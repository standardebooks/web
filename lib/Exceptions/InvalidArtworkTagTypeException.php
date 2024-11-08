<?
namespace Exceptions;

class InvalidArtworkTagTypeException extends AppException{
	/** @var string $message */
	protected $message = 'Type should be `Enums\TagType::Artwork`.';

	public function __construct(?\Enums\TagType $tagType){
		if($tagType !== null){
			$this->message .= ' Type provided: ' . $tagType->value;
		}

		parent::__construct($this->message);
	}
}
