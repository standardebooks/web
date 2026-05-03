<?
namespace Exceptions;

class SpreadsheetSortOrderRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'Sort order is required.';
}
