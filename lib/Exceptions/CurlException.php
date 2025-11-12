<?
namespace Exceptions;

class CurlException extends AppException{
	/** @var string $message */
	protected $message = 'Error making HTTP request.';
}
