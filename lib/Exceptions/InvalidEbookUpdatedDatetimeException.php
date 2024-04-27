<?
namespace Exceptions;

use Safe\DateTimeImmutable;

class InvalidEbookUpdatedDatetimeException extends AppException{
	protected $message = 'Invalid EbookUpdated datetime.';

	public function __construct(DateTimeImmutable $updatedDatetime){
		$now = new DateTimeImmutable();
		$this->message = 'Invalid EbookUpdated datetime. ' . $updatedDatetime->format('Y-m-d') . ' is not between ' . EBOOK_EARLIEST_CREATION_DATE->format('Y-m-d') . ' and ' . $now->format('Y-m-d') . '.';
	}
}
