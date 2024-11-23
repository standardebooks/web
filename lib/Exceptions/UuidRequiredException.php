<?
namespace Exceptions;

class UuidRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'A UUID is required.';
}
