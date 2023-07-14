<?
namespace Exceptions;

class InvalidNewsletterSubscriptionException extends AppException{
	protected $message = 'We couldn’t find you in our newsletter subscribers list.';
}
