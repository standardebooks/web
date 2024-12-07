<?
namespace Exceptions;

class EbookUnexpectedPlaceholderException extends AppException{
	/** @var string $message */
	protected $message = 'Ebook is not a placeholder, but has a placeholder object.';
}
