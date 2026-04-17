<?
namespace Exceptions;

class PollVoteNotFoundException extends NotFoundException{
	/** @var string $message */
	protected $message = 'We couldn’t locate that vote.';
}
