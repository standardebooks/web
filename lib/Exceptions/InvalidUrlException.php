<?
namespace Exceptions;

class InvalidUrlException extends AppException{
	/** @var string $message */
	protected $message = 'Invalid URL.';

	public function __construct(?string $url = null){
		if($url !== null){
			parent::__construct('Invalid URL: <' . $url . '>.');
		}
		else{
			parent::__construct();
		}
	}
}
