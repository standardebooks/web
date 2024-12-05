<?
namespace Exceptions;

class EbookMissingPlaceholderException extends AppException{
	/** @var string $message */
	protected $message = 'Ebook is a placeholder, but has no placeholder object.';
}
