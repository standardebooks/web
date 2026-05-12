<?
namespace Exceptions;

class ArtworkInvalidException extends ValidationException{
	/** @var string $message */
	protected $message = 'Artwork is invalid.';
}
