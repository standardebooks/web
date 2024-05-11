<?
namespace Exceptions;

class UserExistsException extends AppException{
	/** @var string $message */
	protected $message = 'This email already exists in the database.';
}
