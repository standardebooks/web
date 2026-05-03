<?
namespace Exceptions;

class SpreadsheetNotFoundException extends NotFoundException{
	/** @var string $message */
	protected $message = 'We couldn’t locate that spreadsheet.';
}
