<?
namespace Exceptions;

class InvalidNewsletterSendOnException extends FieldInvalidException{
	/** @var string $message */
	protected $message = 'Invalid send on time.';
}
