<?
namespace Exceptions;

class InvalidNewsletterMailingBodyHtmlException extends FieldInvalidException{
	/** @var string $message */
	protected $message = 'Body HTML is invalid.';

	public function __construct(?string $message = null, bool $isFatal = false){
		if($message !== null){
			$this->message = 'Body HTML is invalid: ' . $message;
		}

		$this->IsFatal = $isFatal;

		parent::__construct();
	}
}
