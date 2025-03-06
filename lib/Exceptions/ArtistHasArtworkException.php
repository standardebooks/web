<?
namespace Exceptions;

class ArtistHasArtworkException extends AppException{
	/** @var string $message */
	protected $message = 'Artist has artwork assoicated with it. Can’t delete.';
}
