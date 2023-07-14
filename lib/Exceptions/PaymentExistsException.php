<?
namespace Exceptions;

class PaymentExistsException extends AppException{
	protected $message = 'This transaction ID already exists.';
}
