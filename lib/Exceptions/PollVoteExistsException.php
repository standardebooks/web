<?
namespace Exceptions;

class PollVoteExistsException extends SeException{
	public $Vote = null;
	protected $message = 'You’ve already voted in this poll.';

	public function __construct(?\PollVote $vote = null){
		$this->Vote = $vote;
	}
}
