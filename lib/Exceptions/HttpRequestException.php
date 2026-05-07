<?
namespace Exceptions;

class HttpRequestException extends AppException{
	/** @var string $message */
	protected $message = 'Error making HTTP request.';
}
