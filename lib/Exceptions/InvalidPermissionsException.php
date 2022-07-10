<?
namespace Exceptions;

class InvalidPermissionsException extends SeException{
	protected $message = 'You don’t have permission to perform that action.';
}
