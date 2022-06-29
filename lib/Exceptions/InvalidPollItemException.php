<?
namespace Exceptions;

class InvalidPollItemException extends SeException{
	protected $message = 'We couldn’t locate that poll item.';
}
