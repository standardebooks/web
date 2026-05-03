<?
namespace Exceptions;

class InvalidSpreadsheetUrlException extends InvalidUrlException{
	/** @var string $message */
	protected $message = 'Invalid URL.';
}
