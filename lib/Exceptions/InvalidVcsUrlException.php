<?
namespace Exceptions;

class InvalidVcsUrlException extends InvalidUrlException{
	/** @var string $message */
	protected $message = 'Invalid VCS URL.';
}
