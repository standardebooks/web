<?
namespace Exceptions;

class DuplicateDatabaseKeyException extends AppException{
	protected $message = 'An attempted row insertion has violated the database unique index.';
}
