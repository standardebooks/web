<?
namespace Exceptions;

class InvalidUuidException extends AppException{
	/** @var string $message */
	protected $message = 'Invalid UUID.';
}
