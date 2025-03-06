<?
namespace Exceptions;

class ArtistNotFoundException extends AppException{
	/** @var string $message */
	protected $message = 'We couldn’t locate that artist.';

	public function __construct(?string $additionalMessage = null){
		if($additionalMessage !== null && trim($additionalMessage) != ''){
			$this->message .= ' ' . $additionalMessage;
		}

		parent::__construct($this->message);
	}
}
