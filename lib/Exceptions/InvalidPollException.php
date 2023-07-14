<?
namespace Exceptions;

class InvalidPollException extends AppException{
	protected $message = 'We couldn’t locate that poll.';
}
