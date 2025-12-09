<?
namespace Exceptions;

class InvalidEmailAddressException extends AppException{
	/** @var string $message */
	protected $message = 'We couldn’t understand your email address.';
}
