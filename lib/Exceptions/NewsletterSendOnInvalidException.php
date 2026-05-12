<?
namespace Exceptions;

class NewsletterSendOnInvalidException extends FieldInvalidException{
	/** @var string $message */
	protected $message = 'Invalid send on time.';
}
