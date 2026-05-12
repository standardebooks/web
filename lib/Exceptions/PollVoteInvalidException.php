<?
namespace Exceptions;

class PollVoteInvalidException extends ValidationException{
	/** @var string $message */
	protected $message = 'Vote is invalid.';
}
