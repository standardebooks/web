<?
namespace Exceptions;

class InvalidLocSubjectException extends ValidationException{
	/** @var string $message */
	protected $message = 'LoC Subject is invalid.';
}
