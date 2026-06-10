<?
namespace Exceptions;

class PollDateInvalidException extends AppException{
	/** @var string $message */
	protected $message = 'Poll end date must be after the start date.';
}
