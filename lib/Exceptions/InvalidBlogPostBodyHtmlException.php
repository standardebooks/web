<?
namespace Exceptions;

class InvalidBlogPostBodyHtmlException extends AppException{
	/** @var string $message */
	protected $message = 'Blog post HTML is invalid.';

	public function __construct(?string $message = null){
		if($message !== null){
			$this->message = 'Blog post HTML is invalid: ' . $message;
		}

		parent::__construct();
	}
}
