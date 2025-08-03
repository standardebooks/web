<?
namespace Exceptions;

class InvalidDiscussionUrlException extends InvalidUrlException{
	/** @var string $message */
	protected $message = 'Invalid discussion URL.';

	public function __construct(?string $url = null){
		if($url !== null){
			$this->message = 'Invalid discussion URL: <' . $url . '>.';
		}

		parent::__construct();
	}
}
