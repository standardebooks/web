<?
namespace Exceptions;

use Safe\DateTimeImmutable;

class InvalidGitCommitCreatedDatetimeException extends AppException{
	/** @var string $message */
	protected $message = 'Invalid GitCommit Created datetime.';

	public function __construct(DateTimeImmutable $createdDatetime){
		/** @throws void */
		$now = new DateTimeImmutable();
		$this->message = 'Invalid GitCommit Created datetime. ' . $createdDatetime->format('Y-m-d') . ' is after ' . $now->format('Y-m-d') . '.';
	}
}
