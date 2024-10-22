<?
namespace Exceptions;

use Safe\DateTimeImmutable;

class InvalidEbookCreatedDatetimeException extends AppException{
	/** @var string $message */
	protected $message = 'Invalid EbookCreated datetime.';

	public function __construct(DateTimeImmutable $createdDatetime){
		/** @throws void */
		$now = new DateTimeImmutable();
		$this->message = 'Invalid EbookCreated datetime. ' . $createdDatetime->format('Y-m-d') . ' is after ' . $now->format('Y-m-d') . '.';
	}
}
