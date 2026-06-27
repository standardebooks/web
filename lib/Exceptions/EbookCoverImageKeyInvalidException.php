<?
namespace Exceptions;

/**
 * An exception representing an invalid ebook cover image key.
 */
class EbookCoverImageKeyInvalidException extends AppException{
	/** @var string $message */
	protected $message = 'Couldn’t calculate cover image key.';
}
