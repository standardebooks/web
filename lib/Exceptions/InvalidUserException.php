<?
namespace Exceptions;

class InvalidUserException extends AppException{
	protected $message = 'We couldn’t validate your login information.';
}
