<?
namespace Exceptions;

class PollVoteNotFoundException extends AppException{
	/** @var string $message */
	protected $message = 'We couldn’t locate that vote.';
}
