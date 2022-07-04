<?
namespace Exceptions;

class InvalidVoteException extends SeException{
	protected $message = 'We couldn’t locate that vote.';
}
