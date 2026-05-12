<?
namespace Exceptions;

class RequestInvalidException extends AppException{
	/** @var string $message */
	protected $message = 'Invalid HTTP request.';
}
