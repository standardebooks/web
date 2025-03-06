<?
namespace Exceptions;

class ArtistAlternateNameExistsException extends AppException{
	/** @var string $message */
	protected $message = 'Artist already has that alternate name (A.K.A.).';
}
