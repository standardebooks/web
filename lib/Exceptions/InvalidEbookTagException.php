<?
namespace Exceptions;

class InvalidEbookTagException extends ValidationException{
	/** @var string $message */
	protected $message = 'Ebook tag is invalid.';
}
