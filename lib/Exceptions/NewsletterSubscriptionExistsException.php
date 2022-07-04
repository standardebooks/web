<?
namespace Exceptions;

class NewsletterSubscriptionExistsException extends SeException{
	protected $message = 'You’re already subscribed to the newsletter.';
}
