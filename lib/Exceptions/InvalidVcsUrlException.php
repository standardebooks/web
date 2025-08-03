<?
namespace Exceptions;

class InvalidVcsUrlException extends InvalidUrlException{
	/** @var string $message */
	protected $message = 'Invalid VCS URL.';

	public function __construct(?string $url = null){
		if($url !== null){
			$this->message = 'Invalid VCS URL: <' . $url . '>.';
		}

		parent::__construct();
	}
}
