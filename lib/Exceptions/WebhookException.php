<?
namespace Exceptions;

class WebhookException extends AppException{
	public ?string $PostData;

	public function __construct(string $message = '', ?string $data = null){
		$this->PostData = $data;
		parent::__construct($message);
	}
}
