<?
namespace Exceptions;

class InvalidUserException extends AppException{
	protected $message = 'We couldn’t locate that user.';
}
