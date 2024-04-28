<?
namespace Exceptions;

class InvalidPollVoteException extends ValidationException{
	protected $message = 'Vote is invalid.';
}
