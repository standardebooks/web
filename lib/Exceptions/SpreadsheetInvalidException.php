<?
namespace Exceptions;

class SpreadsheetInvalidException extends ValidationException{
	/** @var string $message */
	protected $message = 'Spreadsheet is invalid.';
}
