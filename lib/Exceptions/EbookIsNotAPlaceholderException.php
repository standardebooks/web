<?
namespace Exceptions;

class EbookIsNotAPlaceholderException extends AppException{
	/** @var string $message */
	protected $message = 'This ebook is already released, and is not a placeholder.';
}
