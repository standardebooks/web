<?
namespace Exceptions;

class UserInvalidException extends ValidationException{
	/** @var string $message */
	protected $message = 'User is invalid.';
}
