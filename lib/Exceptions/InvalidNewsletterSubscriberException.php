<?
namespace Exceptions;

class InvalidNewsletterSubscriberException extends SeException{
	protected $message = 'We couldn’t find you in our newsletter subscribers list.';
}
