<?
namespace Exceptions;

class InvalidPollItemException extends AppException{
	protected $message = 'We couldn’t locate that poll item.';
}
