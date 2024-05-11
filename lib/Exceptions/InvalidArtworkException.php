<?
namespace Exceptions;

class InvalidArtworkException extends ValidationException{
	/** @var string $message */
	protected $message = 'Artwork is invalid.';
}
