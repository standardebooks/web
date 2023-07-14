<?
namespace Exceptions;

class InvalidPermissionsException extends AppException{
	protected $message = 'You don’t have permission to perform that action.';
}
