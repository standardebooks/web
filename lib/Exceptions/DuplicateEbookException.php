<?
namespace Exceptions;

class DuplicateEbookException extends AppException{
	public function __construct(string $identifier){
		$this->message = 'Ebook already exists with identifier: ' . $identifier;

		parent::__construct($this->message);
	}
}
