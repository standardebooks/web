<?
namespace Exceptions;

class PollNotFoundException extends NotFoundException{
	/** @var string $message */
	protected $message = 'We couldn’t locate that poll.';
}
