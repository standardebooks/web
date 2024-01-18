<?
namespace Exceptions;

class NewsletterSubscriptionNotFoundException extends AppException{
	protected $message = 'We couldn’t find you in our newsletter subscribers list.';
}
