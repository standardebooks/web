<?
namespace Exceptions;

class ArtworkTagNameRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'Tag name is required.';
}
