<?
namespace Exceptions;

class NewsletterSubscriptionExistsException extends AppException{
	/** @var string $message */
	protected $message = 'You’re already subscribed to the newsletter.';
}
