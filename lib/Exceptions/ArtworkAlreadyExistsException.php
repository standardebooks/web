<?
namespace Exceptions;

class ArtworkAlreadyExistsException extends AppException{
	/** @var string $message */
	protected $message = 'An artwork with this name already exists.';
}
