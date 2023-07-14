<?
namespace Exceptions;

class UserExistsException extends AppException{
	protected $message = 'This email already exists in the database.';
}
