<?
namespace Exceptions;

class ArtistNotFoundException extends AppException{
	protected $message = 'We couldn’t locate that artist.';
}
