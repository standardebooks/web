<?
namespace Exceptions;

class GitCommitHashRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'GitCommit hash required.';
}
