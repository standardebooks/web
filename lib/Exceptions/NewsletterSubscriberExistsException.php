<?
namespace Exceptions;

class NewsletterSubscriberExistsException extends SeException{
	protected $message = 'You’re already subscribed to the newsletter.';
}
