<?
namespace Exceptions;

class InvalidPollVoteException extends SeException{
	protected $message = 'We couldn’t locate that vote.';
}
