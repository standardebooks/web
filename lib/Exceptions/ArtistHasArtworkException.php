<?
namespace Exceptions;

class ArtistHasArtworkException extends AppException{
	/** @var string $message */
	protected $message = 'Artist has associated artwork and cannot be deleted.';
}
