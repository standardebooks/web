<?
namespace Exceptions;

class SpreadsheetCategoryRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'Category is required.';
}
