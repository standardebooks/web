<?
namespace Exceptions;

class InvalidArtistException extends ValidationException{
	protected $message = 'Artist is invalid.';
}
