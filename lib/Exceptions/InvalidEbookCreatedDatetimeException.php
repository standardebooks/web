<?
namespace Exceptions;

use Safe\DateTimeImmutable;

class InvalidEbookCreatedDatetimeException extends AppException{
	protected $message = 'Invalid EbookCreated datetime.';

	public function __construct(DateTimeImmutable $createdDatetime){
		$now = new DateTimeImmutable();
		$this->message = 'Invalid EbookCreated datetime. ' . $createdDatetime->format('Y-m-d') . ' is not between ' . EBOOK_EARLIEST_CREATION_DATE->format('Y-m-d') . ' and ' . $now->format('Y-m-d') . '.';
	}
}
