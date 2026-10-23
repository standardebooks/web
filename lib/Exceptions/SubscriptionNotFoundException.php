<?
namespace Exceptions;

class SubscriptionNotFoundException extends NotFoundException{
	/** @var string $message */
	protected $message = 'We couldn’t locate that subscription.';
}
