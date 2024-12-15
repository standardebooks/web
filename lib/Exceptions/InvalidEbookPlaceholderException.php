<?
namespace Exceptions;

class InvalidEbookPlaceholderException extends ValidationException{
	/** @var string $message */
	protected $message = 'Ebook placeholder is invalid.';
}
