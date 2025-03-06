<?
namespace Exceptions;

class ArtistNotFoundException extends AppException{
	/** @var string $message */
	protected $message = 'We couldn’t locate that artist.';
}
