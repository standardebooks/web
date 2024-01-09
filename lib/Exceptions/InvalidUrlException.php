<?
namespace Exceptions;

class InvalidUrlException extends AppException{
	protected $message = 'Invalid URL.';

	public function __construct(?string $url = null){
		if($url !== null){
			parent::__construct('Invalid URL: “' . $url . '”.');
		}
	}
}
