<?
namespace Exceptions;

class PollVoteExistsException extends AppException{
	public ?\PollVote $Vote = null;
	/** @var string $message */
	protected $message = 'You’ve already voted in this poll.';

	public function __construct(?\PollVote $vote = null){
		$this->Vote = $vote;
	}
}
