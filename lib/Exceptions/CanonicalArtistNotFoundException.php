<?
namespace Exceptions;

class CanonicalArtistNotFoundException extends NotFoundException{
	/** @var string $message */
	protected $message = 'We couldn’t locate that artist.';
}
