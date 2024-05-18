<?
namespace Exceptions;

class NewsletterSubscriptionNotFoundException extends AppException{
	/** @var string $message */
	protected $message = 'We couldn’t find you in our newsletter subscribers list.';
}
