<?
namespace Exceptions;

class ArtworkNotFoundException extends AppException{
	/** @var string $message */
	protected $message = 'We couldn’t locate that artwork.';
}
