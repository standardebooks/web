<?
namespace Exceptions;

class ArtworkAlreadyExistsException extends AppException{
	protected $message = 'An artwork with this name already exists.';
}
