<?
namespace Exceptions;

class InvalidEmailException extends AppException{
	protected $message = 'We couldn’t understand your email address.';
}
