<?
namespace Exceptions;

use Safe\DateTimeImmutable;

class EbookUpdatedAtDatetimeInvalidException extends AppException{
	/** @var string $message */
	protected $message = 'Invalid EbookUpdatedAt datetime.';

	public function __construct(DateTimeImmutable $updatedDatetime){
		$this->message = 'Invalid EbookUpdatedAt datetime. ' . $updatedDatetime->format('Y-m-d') . ' is after ' . NOW->format('Y-m-d') . '.';

		parent::__construct($this->message);
	}
}
