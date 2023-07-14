<?
namespace Exceptions;

class InvalidUserException extends AppException{
	protected $message = 'We couldn’t locate you in our system.';
}
