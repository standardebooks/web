<?
namespace Exceptions;

class UserExistsException extends SeException{
	protected $message = 'This email already exists in the database.';
}
