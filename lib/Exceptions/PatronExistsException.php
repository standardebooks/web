<?
namespace Exceptions;

class PatronExistsException extends AppException{
	/** @var string $message */
	protected $message = 'That user is already a Patron.';
}
