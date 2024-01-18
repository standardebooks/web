<?
namespace Exceptions;

class PollVoteNotFoundException extends AppException{
	protected $message = 'We couldn’t locate that vote.';
}
