<?
namespace Exceptions;

class PageOutOfBoundsException extends AppException{
	public int $RealPageNumber;

	public function __construct(string $message = '', int $code = 0, \Throwable|null $previous = null, int $realPageNumber = 0){
		$this->RealPageNumber = $realPageNumber;
		parent::__construct($message, $code, $previous);
	}
}
