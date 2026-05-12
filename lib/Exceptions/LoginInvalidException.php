<?
namespace Exceptions;

class LoginInvalidException extends AppException{
	/** @var string $message */
	protected $message = 'We couldn’t validate your login information.';
}
