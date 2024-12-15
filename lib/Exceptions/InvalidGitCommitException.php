<?
namespace Exceptions;

class InvalidGitCommitException extends ValidationException{
	/** @var string $message */
	protected $message = 'Git commit is invalid.';
}
