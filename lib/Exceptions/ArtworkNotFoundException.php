<?
namespace Exceptions;

class ArtworkNotFoundException extends NotFoundException{
	/** @var string $message */
	protected $message = 'We couldn’t locate that artwork.';
}
