<?
namespace Exceptions;

class SeeOtherException extends AppException{
	public string $Url;

	public function __construct(string $url){
		$this->Url = $url;

		parent::__construct();
	}
}
