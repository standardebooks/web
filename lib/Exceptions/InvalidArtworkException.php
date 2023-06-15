<?
namespace Exceptions;

class InvalidArtworkException extends SeException{
	public $message;

	public function __construct(?string $message = null){
		parent::__construct();

		$this->message = $message;
	}
}
