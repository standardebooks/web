<?
namespace Exceptions;

class EmailRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'An email is required.';
}
