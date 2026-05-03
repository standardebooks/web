<?
namespace Exceptions;

class InvalidSpreadsheetException extends ValidationException{
	/** @var string $message */
	protected $message = 'Spreadsheet is invalid.';
}
