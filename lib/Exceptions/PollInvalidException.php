<?
namespace Exceptions;

class PollInvalidException extends ValidationException{
	/** @var string $message */
	protected $message = 'Poll is invalid.';
}
