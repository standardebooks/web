<?
namespace Exceptions;

class PollClosedException extends AppException{
	/** @var string $message */
	protected $message = 'This poll is not open to voting right now.';
}
