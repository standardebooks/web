<?
namespace Exceptions;

class PollItemRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'You must select an item to vote on.';
}
