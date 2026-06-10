<?
namespace Exceptions;

class PollItemsRequiredException extends FieldMissingException{
	/** @var string $message */
	protected $message = 'At least two poll options are required.';
}
