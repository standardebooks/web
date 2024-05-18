<?
namespace Exceptions;

class InvalidPollVoteException extends ValidationException{
	/** @var string $message */
	protected $message = 'Vote is invalid.';
}
