<?
namespace Exceptions;

class GitCommitMessageRequiredException extends AppException{
	/** @var string $message */
	protected $message = 'GitCommit message required.';
}
