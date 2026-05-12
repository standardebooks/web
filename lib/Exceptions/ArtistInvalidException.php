<?
namespace Exceptions;

class ArtistInvalidException extends ValidationException{
	/** @var string $message */
	protected $message = 'Artist is invalid.';
}
