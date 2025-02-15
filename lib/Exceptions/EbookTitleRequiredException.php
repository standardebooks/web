<?
namespace Exceptions;

class EbookTitleRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'Title required.';
}
