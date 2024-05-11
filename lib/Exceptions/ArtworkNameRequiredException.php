<?
namespace Exceptions;

class ArtworkNameRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'An artwork name is required.';
}
