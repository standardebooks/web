<?
namespace Exceptions;

class InvalidUserException extends ValidationException{
	/** @var string $message */
	protected $message = 'User is invalid.';
}
