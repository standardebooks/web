<?
namespace Exceptions;

use Safe\DateTimeImmutable;

class InvalidEbookCreatedDatetimeException extends AppException{
	/** @var string $message */
	protected $message = 'Invalid EbookCreated datetime.';

	public function __construct(DateTimeImmutable $createdDatetime = null){
		if(isset($createdDatetime)){
			$this->message = 'Invalid EbookCreated datetime. ' . $createdDatetime->format('Y-m-d') . ' is after ' . NOW->format('Y-m-d') . '.';
		}
		else{
			$this->message = 'Invalid EbookCreated datetime: null';
		}
	}
}
