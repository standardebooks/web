<?php

namespace Exceptions;

class InvalidCollectionTypeException extends AppException{
	/** @var string $message */
	protected $message = 'Collection type should be `series` or `set` according to the EPUB specification.';

	public function __construct(?string $collectionType){
		if($collectionType !== null && trim($collectionType) != ''){
			$this->message .= ' Type provided: ' . $collectionType;
		}

		parent::__construct($this->message);
	}
}
