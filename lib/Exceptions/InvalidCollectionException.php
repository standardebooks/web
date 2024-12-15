<?
namespace Exceptions;

class InvalidCollectionException extends ValidationException{
	/** @var string $message */
	protected $message = 'Collection is invalid.';
}
