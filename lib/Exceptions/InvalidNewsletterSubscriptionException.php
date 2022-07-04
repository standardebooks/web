<?
namespace Exceptions;

class InvalidNewsletterSubscriptionException extends SeException{
	protected $message = 'We couldn’t find you in our newsletter subscribers list.';
}
