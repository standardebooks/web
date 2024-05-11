<?
namespace Exceptions;

class PaymentExistsException extends AppException{
	/** @var string $message */
	protected $message = 'This transaction ID already exists.';
}
