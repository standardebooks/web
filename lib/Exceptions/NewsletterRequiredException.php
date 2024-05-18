<?
namespace Exceptions;

class NewsletterRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'You must select at least one newsletter to subscribe to.';
}
