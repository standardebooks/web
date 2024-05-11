<?
namespace Exceptions;

class InvalidArtworkTagNameException extends AppException{
	/** @var string $message */
	protected $message = 'Artwork tags can only contain letters and numbers.';
}
