<?
namespace Exceptions;

class InvalidArtistException extends AppException{
	protected $message = 'We couldn’t locate that artist.';
}
