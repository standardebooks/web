<?
namespace Exceptions;

class PollDateRequiredException extends FieldMissingException{
	/** @var string $message */
	protected $message = 'Start and end dates are required.';
}
