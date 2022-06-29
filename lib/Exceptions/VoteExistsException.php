<?
namespace Exceptions;

class VoteExistsException extends SeException{
	protected $message = 'You’ve already voted in this poll.';
}
