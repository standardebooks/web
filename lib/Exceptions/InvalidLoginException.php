<?
namespace Exceptions;

class InvalidLoginException extends AppException{
	protected $message = 'We couldn’t validate your login information.';
}
