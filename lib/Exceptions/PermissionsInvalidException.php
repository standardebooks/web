<?
namespace Exceptions;

class PermissionsInvalidException extends AppException{
	/** @var string $message */
	protected $message = 'You don’t have permission to perform that action.';
}
