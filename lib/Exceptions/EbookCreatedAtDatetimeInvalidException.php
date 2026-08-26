<?
namespace Exceptions;

use Safe\DateTimeImmutable;

class EbookCreatedAtDatetimeInvalidException extends AppException{
	/** @var string $message */
	protected $message = 'Invalid EbookCreatedAt datetime.';

	public function __construct(?DateTimeImmutable $createdDatetime = null){
		if(isset($createdDatetime)){
			$this->message = 'Invalid EbookCreatedAt datetime. ' . $createdDatetime->format('Y-m-d') . ' is after ' . NOW->format('Y-m-d') . '.';
		}
		else{
			$this->message = 'Invalid EbookCreatedAt datetime: null';
		}
	}
}
