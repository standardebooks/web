<?
namespace Exceptions;

class SpreadsheetExistsException extends AppException{
	/** @var string $message */
	protected $message = 'There’s already a spreadsheet with that URL.';
}
