<?
namespace Exceptions;

class ArtistNotFoundException extends NotFoundException{
	/** @var string $message */
	protected $message = 'We couldn’t locate that artist.';
}
