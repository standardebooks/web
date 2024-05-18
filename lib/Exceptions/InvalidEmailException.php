<?
namespace Exceptions;

class InvalidEmailException extends AppException{
	/** @var string $message */
	protected $message = 'We couldn’t understand your email address.';
}
