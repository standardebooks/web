<?
namespace Exceptions;

class PaymentExistsException extends SeException{
	protected $message = 'This transaction ID already exists.';
}
