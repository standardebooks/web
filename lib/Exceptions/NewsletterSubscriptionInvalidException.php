<?
namespace Exceptions;

class NewsletterSubscriptionInvalidException extends ValidationException{
	/** @var string $message */
	protected $message = 'Newsletter subscription is invalid.';
}
