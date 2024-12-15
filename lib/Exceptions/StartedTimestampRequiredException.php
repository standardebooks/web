<?
namespace Exceptions;

class StartedTimestampRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'Started timestamp is required.';
}
