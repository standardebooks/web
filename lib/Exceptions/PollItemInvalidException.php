<?
namespace Exceptions;

class PollItemInvalidException extends ValidationException{
	/** @var string $message */
	protected $message = 'Poll item is invalid.';
}
