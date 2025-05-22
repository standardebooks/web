<?
namespace Exceptions;

class InvalidEbookDownloadException extends ValidationException{
	/** @var string $message */
	protected $message = 'EbookDownload is invalid.';
}
