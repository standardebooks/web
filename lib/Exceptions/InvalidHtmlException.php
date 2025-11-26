<?
namespace Exceptions;

class InvalidHtmlException extends FieldInvalidException{
	/** @var string $message */
	protected $message = 'Invalid HTML.';
	public string $RawMessage = '';

	public function __construct(string $message = ''){
		if($message != ''){
			$this->RawMessage = $message;
			$this->message = 'Invalid HTML: ' . $message;
		}

		parent::__construct();
	}
}
