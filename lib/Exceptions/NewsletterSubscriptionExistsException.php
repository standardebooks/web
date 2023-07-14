<?
namespace Exceptions;

class NewsletterSubscriptionExistsException extends AppException{
	protected $message = 'You’re already subscribed to the newsletter.';
}
