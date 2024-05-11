<?
namespace Exceptions;

class InvalidArtworkPageUrlException extends InvalidUrlException{
	/** @var string $message */
	protected $message = 'Invalid link to page with artwork.';
}
