<?
namespace Exceptions;

class InvalidPageScanUrlException extends InvalidUrlException{
	public function __construct(string $url, string $exampleUrl){
		$this->message = 'Invalid page scan URL: <' . $url . '>. Expected a URL like: <'. $exampleUrl . '>.';

		parent::__construct($this->message);
	}
}
