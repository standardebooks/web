<?
namespace Exceptions;

class PollNameRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'Poll name is required.';
}
