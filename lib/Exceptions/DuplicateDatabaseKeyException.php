<?
namespace Exceptions;

class DuplicateDatabaseKeyException extends DatabaseQueryException{
	/** @var string $message */
	protected $message = 'An attempted row insertion has violated the database unique index.';
}
