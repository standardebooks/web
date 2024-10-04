<?
namespace Exceptions;

class EbookTagNameRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'EbookTag name is required.';
}
