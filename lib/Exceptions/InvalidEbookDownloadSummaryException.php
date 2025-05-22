<?
namespace Exceptions;

class InvalidEbookDownloadSummaryException extends ValidationException{
	/** @var string $message */
	protected $message = 'EbookDownloadSummary is invalid.';
}
