<?
namespace Exceptions;

class PollVoteExistsException extends SeException{
	protected $message = 'You’ve already voted in this poll.';
}
