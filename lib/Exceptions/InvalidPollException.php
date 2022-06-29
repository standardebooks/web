<?
namespace Exceptions;

class InvalidPollException extends SeException{
	protected $message = 'We couldn’t locate that poll.';
}
