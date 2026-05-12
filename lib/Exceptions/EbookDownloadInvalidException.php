<?
namespace Exceptions;

class EbookDownloadInvalidException extends ValidationException{
	/** @var string $message */
	protected $message = 'EbookDownload is invalid.';
}
