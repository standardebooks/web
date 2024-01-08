<?
namespace Exceptions;

class StringTooLongException extends AppException{
	public string $Source;

	public function __construct(string $source = ''){
		$this->Source = $source;
		parent::__construct('String too long: ' . $source);
	}
}
