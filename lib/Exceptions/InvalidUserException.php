<?
namespace Exceptions;

class InvalidUserException extends SeException{
	protected $message = 'We couldn’t locate you in our system.';
}
