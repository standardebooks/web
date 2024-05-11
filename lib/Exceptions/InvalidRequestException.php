<?
namespace Exceptions;

class InvalidRequestException extends AppException{
	/** @var string $message */
	protected $message = 'Invalid HTTP request.';
}
