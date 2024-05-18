<?
namespace Exceptions;

class TagsRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'At least one tag is required.';
}
