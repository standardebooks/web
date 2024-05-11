<?
namespace Exceptions;

class DuplicateDatabaseKeyException extends AppException{
	/** @var string $message */
	protected $message = 'An attempted row insertion has violated the database unique index.';
}
