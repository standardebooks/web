<?
namespace Exceptions;

class SpreadsheetExternalUrlRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'URL is required.';
}
