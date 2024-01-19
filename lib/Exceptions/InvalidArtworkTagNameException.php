<?
namespace Exceptions;

class InvalidArtworkTagNameException extends AppException{
	protected $message = 'Artwork tags can only contain letters and numbers.';
}
