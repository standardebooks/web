<?
namespace Exceptions;

class InvalidNewsletterSubscription extends ValidationException{
	protected $message = 'Newsletter subscription is invalid.';
}
