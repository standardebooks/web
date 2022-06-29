<?
namespace Exceptions;

class PollItemRequiredException extends SeException{
	protected $message = 'You must select an item to vote on.';
}
