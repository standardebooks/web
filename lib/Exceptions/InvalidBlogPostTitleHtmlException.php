<?
namespace Exceptions;

class InvalidBlogPostTitleHtmlException extends AppException{
	/** @var string $message */
	protected $message = 'Blog post title HTML is invalid.';

	public function __construct(?string $message = null){
		if($message !== null){
			$this->message = 'Blog post title HTML is invalid: ' . $message;
		}

		parent::__construct();
	}
}
