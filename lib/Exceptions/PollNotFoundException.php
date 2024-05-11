<?
namespace Exceptions;

class PollNotFoundException extends AppException{
	/** @var string $message */
	protected $message = 'We couldn’t locate that poll.';
}
