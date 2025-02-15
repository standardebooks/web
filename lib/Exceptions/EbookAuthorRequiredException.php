<?
namespace Exceptions;

class EbookAuthorRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'Author required.';
}
