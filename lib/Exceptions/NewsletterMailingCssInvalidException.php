<?
namespace Exceptions;

class NewsletterMailingCssInvalidException extends FieldInvalidException{
	/** @var string $message */
	protected $message = 'Newsletter CSS is invalid.';

	/**
	 * Construct the exception with optional CSS parser details.
	 */
	public function __construct(?string $message = null){
		if($message !== null){
			$this->message = 'Newsletter CSS is invalid: ' . $message;
		}

		parent::__construct();
	}
}
