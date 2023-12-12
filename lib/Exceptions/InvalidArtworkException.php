<?
namespace Exceptions;

class InvalidArtworkException extends AppException{
	protected $message = 'We couldn’t locate that artwork.';
}
