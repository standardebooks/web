<?
namespace Exceptions;

class InvalidRequestException extends AppException{
	protected $message = 'Invalid HTTP request.';
}
