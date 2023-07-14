<?
namespace Exceptions;

class InvalidPollVoteException extends AppException{
	protected $message = 'We couldn’t locate that vote.';
}
