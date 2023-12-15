<?
namespace Exceptions;

class ArtworkAlreadyExistsException extends AppException{
	public $Url;

	public function __construct(string $url = ''){
		$this->Url = $url;
		parent::__construct('Artwork already exisits: ' . $url);
	}
}
