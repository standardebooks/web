<?
namespace Exceptions;

class NewsletterRequiredException extends SeException{
	protected $message = 'You must select at least one newsletter to subscribe to.';
}
