<?
namespace Exceptions;

class PollItemRequiredException extends AppException{
	protected $message = 'You must select an item to vote on.';
}
