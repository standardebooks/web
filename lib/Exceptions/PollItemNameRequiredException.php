<?
namespace Exceptions;

class PollItemNameRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'Poll option name is required.';
}
