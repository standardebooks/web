<?
namespace Exceptions;

use Safe\DateTimeImmutable;

class InvalidBulkDownloadCollectionUpdatedDatetimeException extends AppException{
	/** @var string $message */
	protected $message = 'Invalid BulkDownloadCollection Updated datetime.';

	public function __construct(DateTimeImmutable $updatedDatetime){
		$this->message = 'Invalid BulkDownloadCollection Updated datetime. ' . $updatedDatetime->format('Y-m-d') . ' is after ' . NOW->format('Y-m-d') . '.';
	}
}
