<?
namespace Exceptions;

class EbookTagInvalidException extends ValidationException{
	/** @var string $message */
	protected $message = 'Ebook tag is invalid.';
}
