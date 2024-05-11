<?
namespace Exceptions;

class InvalidLoginException extends AppException{
	/** @var string $message */
	protected $message = 'We couldn’t validate your login information.';
}
