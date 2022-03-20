<?
namespace Exceptions;

class WebhookException extends SeException{
	public $PostData;

	public function __construct(string $message = '', string $data = null){
		$this->PostData = $data;
		parent::__construct($message);
	}
}
