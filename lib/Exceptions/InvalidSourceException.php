<?
namespace Exceptions;

class InvalidSourceException extends ValidationException{
	/** @var string $message */
	protected $message = 'Source is invalid.';
}
