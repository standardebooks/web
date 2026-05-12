<?
namespace Exceptions;

class MuseumUrlInvalidException extends UrlInvalidException{
	public function __construct(string $url, string $exampleUrl){
		$this->message = 'Invalid museum URL: <' . $url . '>. Expected a URL like: <'. $exampleUrl . '>.';

		parent::__construct();
	}
}
