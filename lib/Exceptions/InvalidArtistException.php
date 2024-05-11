<?
namespace Exceptions;

class InvalidArtistException extends ValidationException{
	/** @var string $message */
	protected $message = 'Artist is invalid.';
}
