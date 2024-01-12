<?
namespace Exceptions;

class InvalidMuseumUrlException extends InvalidUrlException{
	public function __construct(string $url, string $exampleUrl){
		$this->message = 'Invalid museum URL: <' . $url . '>. Expected a URL like: <'. $exampleUrl . '>.';
	}
}
