<?
namespace Exceptions;

class GitCommitInvalidException extends ValidationException{
	/** @var string $message */
	protected $message = 'Git commit is invalid.';
}
