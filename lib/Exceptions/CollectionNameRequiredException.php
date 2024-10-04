<?
namespace Exceptions;

class CollectionNameRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'Collection name is required.';
}
