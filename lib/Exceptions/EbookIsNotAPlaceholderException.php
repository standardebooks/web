<?
namespace Exceptions;

class EbookIsNotAPlaceholderException extends AppException{
	/** @var string $message */
	protected $message = 'This project’s ebook is already released, and not a placeholder.';
}
