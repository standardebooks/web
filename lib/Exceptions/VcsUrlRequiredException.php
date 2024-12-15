<?
namespace Exceptions;

class VcsUrlRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'VCS URL is required.';
}
