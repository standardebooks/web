<?
namespace Exceptions;

use Safe\DateTimeImmutable;

class InvalidGitCommitCreatedDatetimeException extends AppException{
	/** @var string $message */
	protected $message = 'Invalid GitCommit Created datetime.';

	public function __construct(DateTimeImmutable $createdDatetime){
		$this->message = 'Invalid GitCommit Created datetime. ' . $createdDatetime->format('Y-m-d') . ' is after ' . NOW->format('Y-m-d') . '.';
		parent::__construct($this->message);
	}
}
