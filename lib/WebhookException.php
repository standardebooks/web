<?
class WebhookException extends \Exception{
	public $PostData;

	public function __construct($message = '', $data = null){
		$this->PostData = $data;
		parent::__construct($message);
	}
}
