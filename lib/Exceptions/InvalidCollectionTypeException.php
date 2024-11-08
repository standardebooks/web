<?
namespace Exceptions;

class InvalidCollectionTypeException extends AppException{
	/** @var string $message */
	protected $message = 'Collection type should be `series` or `set` according to the EPUB specification.';

	public function __construct(?\Enums\CollectionType $collectionType){
		if($collectionType !== null){
			$this->message .= ' Type provided: ' . $collectionType->value;
		}

		parent::__construct($this->message);
	}
}
