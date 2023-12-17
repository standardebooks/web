<?
namespace Exceptions;

class ArtworkAlreadyExistsException extends AppException{
	public $Url;

	public function __construct(string $url = ''){
		$this->Url = $url;
		$message = 'Artwork already exisits';
		if($this->Url !== null && $this->Url !== ''){
			$message = 'Artwork already exisits: ' . $url;
		}
		parent::__construct($message);
	}
}
