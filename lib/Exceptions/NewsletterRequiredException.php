<?
namespace Exceptions;

class NewsletterRequiredException extends AppException{
	protected $message = 'You must select at least one newsletter to subscribe to.';
}
