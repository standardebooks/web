<?
namespace Exceptions;

class PollExistsException extends AppException{
	/** @var string $message */
	protected $message = 'Poll already exists.';
}
