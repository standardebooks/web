<?
namespace Exceptions;

class InvalidArtworkException extends ValidationException{
	protected $message = 'Artwork is invalid.';
}
