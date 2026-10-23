<?
namespace Exceptions;

class PaymentInvalidException extends ValidationException{
	/** @var string $message */
	protected $message = 'This payment is invalid.';
}
