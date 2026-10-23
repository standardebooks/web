<?
namespace Exceptions;

class SubscriptionInvalidException extends ValidationException{
	/** @var string $message */
	protected $message = 'Subscription is invalid.';
}
