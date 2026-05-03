<?
namespace Exceptions;

class SpreadsheetTitleRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'Title is required.';
}
