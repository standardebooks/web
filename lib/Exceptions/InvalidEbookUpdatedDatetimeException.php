<?
namespace Exceptions;

use Safe\DateTimeImmutable;

class InvalidEbookUpdatedDatetimeException extends AppException{
	/** @var string $message */
	protected $message = 'Invalid EbookUpdated datetime.';

	public function __construct(DateTimeImmutable $updatedDatetime){
		$this->message = 'Invalid EbookUpdated datetime. ' . $updatedDatetime->format('Y-m-d') . ' is after ' . NOW->format('Y-m-d') . '.';

		parent::__construct($this->message);
	}
}
