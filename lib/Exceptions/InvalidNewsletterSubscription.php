<?
namespace Exceptions;

class InvalidNewsletterSubscription extends ValidationException{
	/** @var string $message */
	protected $message = 'Newsletter subscription is invalid.';
}
