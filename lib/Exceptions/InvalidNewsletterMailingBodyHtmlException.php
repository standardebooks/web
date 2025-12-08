<?
namespace Exceptions;

class InvalidNewsletterMailingBodyHtmlException extends FieldInvalidException{
	/** @var string $message */
	protected $message = 'Body HTML is invalid.';

	public function __construct(?string $message = null){
		if($message !== null){
			$this->message = 'Body HTML is invalid: ' . $message;
		}

		parent::__construct();
	}
}
