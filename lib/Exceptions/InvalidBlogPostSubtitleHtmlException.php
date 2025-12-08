<?
namespace Exceptions;

class InvalidBlogPostSubtitleHtmlException extends AppException{
	/** @var string $message */
	protected $message = 'Blog post subtitle HTML is invalid.';

	public function __construct(?string $message = null){
		if($message !== null){
			$this->message = 'Blog post sybtitle HTML is invalid: ' . $message;
		}

		parent::__construct();
	}
}
