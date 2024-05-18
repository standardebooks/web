<?
namespace Exceptions;

class ArtistNameRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'An artist name is required.';
}
