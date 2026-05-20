<?
namespace Exceptions;

class PageOutOfBoundsException extends AppException{
	public int $TotalPages;

	public function __construct(string $message = "", int $code = 0, \Throwable|null $previous = null, int $totalPages = 0){
		$this->TotalPages = $totalPages;
		parent::__construct($message, $code, $previous);
	}
}
