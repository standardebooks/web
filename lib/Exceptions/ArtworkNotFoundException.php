<?
namespace Exceptions;

class ArtworkNotFoundException extends AppException{
	protected $message = 'We couldn’t locate that artwork.';
}
