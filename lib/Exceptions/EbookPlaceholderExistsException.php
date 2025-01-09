<?
namespace Exceptions;

class EbookPlaceholderExistsException extends AppException{
	/** @var string $message */
	protected $message = 'An ebook placeholder for this book already exists.';
}
