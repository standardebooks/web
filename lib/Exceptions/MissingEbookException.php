<?
namespace Exceptions;

class MissingEbookException extends AppException{
	/** @var string $message */
	protected $message = 'Artwork marked as “in use”, but the ebook couldn’t be found in the filesystem.';
}
